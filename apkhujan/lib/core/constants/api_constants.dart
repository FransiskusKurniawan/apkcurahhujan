class ApiConstants {
  // Using localhost since you are running on Flutter Web
  static const String baseUrl = 'http://127.0.0.1:8000/api';
  
  // Auth endpoints
  static const String login = '$baseUrl/login';
  static const String register = '$baseUrl/register';
  static const String logout = '$baseUrl/logout';
  static const String changePassword = '$baseUrl/change-password';
  static const String sensor = '$baseUrl/sensor';
}
