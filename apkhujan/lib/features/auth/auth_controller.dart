import 'package:flutter/material.dart';

class AuthController {
  // Login form key
  static final GlobalKey<FormState> loginFormKey = GlobalKey<FormState>();

  // Change Password form key
  static final GlobalKey<FormState> changePassFormKey = GlobalKey<FormState>();

  // Text controllers
  static final TextEditingController usernameController = TextEditingController();
  static final TextEditingController passwordController =
      TextEditingController();
  
  // Change Password controllers
  static final TextEditingController oldPasswordController = TextEditingController();
  static final TextEditingController newPasswordController = TextEditingController();
  static final TextEditingController confirmPasswordController = TextEditingController();

  // Form validators
  static String? validateUsername(String? value) {
    if (value == null || value.isEmpty) {
      return 'Please enter your username';
    }
    return null;
  }

  static String? validateEmail(String? value) {
    if (value == null || value.isEmpty) {
      return 'Please enter your email';
    }
    final emailRegex = RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$');
    if (!emailRegex.hasMatch(value)) {
      return 'Please enter a valid email';
    }
    return null;
  }

  static String? validatePassword(String? value) {
    if (value == null || value.isEmpty) {
      return 'Please enter your password';
    }
    if (value.length < 8) {
      return 'Password must be at least 8 characters';
    }
    return null;
  }

  static String? validateConfirmPassword(String? value, String originalPassword) {
    if (value == null || value.isEmpty) {
      return 'Please confirm your password';
    }
    if (value != originalPassword) {
      return 'Passwords do not match';
    }
    return null;
  }

  // Clean up controllers
  static void dispose() {
    usernameController.dispose();
    passwordController.dispose();
    oldPasswordController.dispose();
    newPasswordController.dispose();
    confirmPasswordController.dispose();
  }
}
