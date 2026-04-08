import 'package:flutter/material.dart';
import 'colors.dart';

class AppTextStyles {
  static const String fontFamily =
      'Roboto'; // Default Flutter font, can be updated if custom font added

  static TextStyle heading(BuildContext context) {
    bool isDarkMode = Theme.of(context).brightness == Brightness.dark;
    return TextStyle(
      fontSize: 28,
      fontWeight: FontWeight.bold,
      color: isDarkMode
          ? AppColors.textPrimaryDark
          : AppColors.textPrimaryLight,
      fontFamily: fontFamily,
    );
  }

  static TextStyle body(BuildContext context) {
    bool isDarkMode = Theme.of(context).brightness == Brightness.dark;
    return TextStyle(
      fontSize: 16,
      color: isDarkMode
          ? AppColors.textSecondaryDark
          : AppColors.textSecondaryLight,
      fontFamily: fontFamily,
      height: 1.5,
    );
  }

  static TextStyle button(BuildContext context, {bool isPrimary = true}) {
    return TextStyle(
      fontSize: 16,
      fontWeight: FontWeight.w600,
      color: isPrimary ? Colors.white : AppColors.primaryBlue,
      fontFamily: fontFamily,
    );
  }
}
