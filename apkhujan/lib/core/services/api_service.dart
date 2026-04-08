import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../constants/api_constants.dart';

class ApiService {
  // Save token after successful login
  static Future<void> saveToken(String token, {String? name, String? username}) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
    if (name != null) await prefs.setString('user_name', name);
    if (username != null) await prefs.setString('user_username', username);
  }

  // Get user details
  static Future<Map<String, String?>> getUserData() async {
    final prefs = await SharedPreferences.getInstance();
    return {
      'name': prefs.getString('user_name'),
      'username': prefs.getString('user_username'),
    };
  }

  // Get token for authenticated requests
  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  // Remove token on logout
  static Future<void> removeToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    await prefs.remove('user_name');
    await prefs.remove('user_username');
  }

  // Login function
  static Future<Map<String, dynamic>> login(String username, String password) async {
    try {
      final response = await http.post(
        Uri.parse(ApiConstants.login),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'username': username,
          'password': password,
        }),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        // Success
        return {
          'success': true,
          'message': data['message'] ?? 'Login successful',
          'token': data['access_token'],
          'user': data['user'],
        };
      } else {
        // Error handling based on Laravel validation exception or auth failure
        String errorMessage = data['message'] ?? 'Login failed';
        if (data['errors'] != null) {
          // If there are validation errors, pick the first one
          final errors = data['errors'] as Map<String, dynamic>;
          if (errors.isNotEmpty) {
            errorMessage = errors.values.first[0];
          }
        }
        return {
          'success': false,
          'message': errorMessage,
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Failed to connect to server. Please check your network connection.',
      };
    }
  }

  // Register function
  static Future<Map<String, dynamic>> register(String username, String password) async {
    try {
      final response = await http.post(
        Uri.parse(ApiConstants.register),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'name': username,
          'username': username,
          'password': password,
        }),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 201 || response.statusCode == 200) {
        return {
          'success': true,
          'message': data['message'] ?? 'Registration successful',
        };
      } else {
        String errorMessage = data['message'] ?? 'Registration failed';
        if (data['errors'] != null) {
          final errors = data['errors'] as Map<String, dynamic>;
          if (errors.isNotEmpty) {
            errorMessage = errors.values.first[0];
          }
        }
        return {
          'success': false,
          'message': errorMessage,
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Failed to connect to server. Please check your network connection.',
      };
    }
  }

  // Fetch Sensor Data
  static Future<Map<String, dynamic>> getSensorData() async {
    try {
      final token = await getToken();
      
      final response = await http.get(
        Uri.parse(ApiConstants.sensor),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          if (token != null) 'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        final List<dynamic> data = jsonDecode(response.body);
        return {
          'success': true,
          // Return the latest data (first item) or empty if no data
          'data': data.isNotEmpty ? data.first : null,
          'all_data': data,
        };
      } else {
        return {
          'success': false,
          'message': 'Failed to fetch data.',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Network error while fetching data.',
      };
    }
  }

  // Change Password function
  static Future<Map<String, dynamic>> changePassword(String oldPassword, String newPassword, String confirmPassword) async {
    try {
      final token = await getToken();
      
      final response = await http.post(
        Uri.parse(ApiConstants.changePassword),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          if (token != null) 'Authorization': 'Bearer $token',
        },
        body: jsonEncode({
          'old_password': oldPassword,
          'new_password': newPassword,
          'new_password_confirmation': confirmPassword,
        }),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        return {
          'success': true,
          'message': data['message'] ?? 'Password changed successfully',
        };
      } else {
        String errorMessage = data['message'] ?? 'Failed to change password';
        if (data['errors'] != null) {
          final errors = data['errors'] as Map<String, dynamic>;
          if (errors.isNotEmpty) {
            errorMessage = errors.values.first[0];
          }
        }
        return {
          'success': false,
          'message': errorMessage,
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Network error while changing password.',
      };
    }
  }
}

