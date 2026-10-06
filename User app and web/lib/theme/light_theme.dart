import 'package:stackfood_multivendor/util/app_constants.dart';
import 'package:stackfood_multivendor/util/app_theme_colors.dart';
import 'package:flutter/material.dart';

ThemeData light = ThemeData(
  useMaterial3: true,
  fontFamily: AppConstants.fontFamily,
  primaryColor: AppThemeColors.primary,
  secondaryHeaderColor: AppThemeColors.primaryMuted,
  highlightColor: AppThemeColors.primary.withValues(alpha: 0.12),
  splashColor: AppThemeColors.primary.withValues(alpha: 0.12),
  disabledColor: const Color(0xFF9B9B9B),
  brightness: Brightness.light,
  hintColor: const Color(0xFF5E6472),
  cardColor: Colors.white,
  shadowColor: Colors.black.withValues(alpha: 0.03),
  progressIndicatorTheme: const ProgressIndicatorThemeData(color: AppThemeColors.primary),
  textButtonTheme: TextButtonThemeData(style: TextButton.styleFrom(foregroundColor: AppThemeColors.primary)),
  filledButtonTheme: FilledButtonThemeData(
    style: FilledButton.styleFrom(backgroundColor: AppThemeColors.primary, foregroundColor: Colors.white),
  ),
  elevatedButtonTheme: ElevatedButtonThemeData(
    style: ElevatedButton.styleFrom(backgroundColor: AppThemeColors.primary, foregroundColor: Colors.white),
  ),
  colorScheme: const ColorScheme.light(primary: AppThemeColors.primary,
    tertiary: Color(0xff102F9C),
    tertiaryContainer: Color(0xff8195DB),
    secondary: AppThemeColors.primary).copyWith(surface: const Color(0xFFF5F6F8)).copyWith(error: const Color(0xFFE84D4F),
  ),
  popupMenuTheme: const PopupMenuThemeData(color: Colors.white, surfaceTintColor: Colors.white),
  dialogTheme: const DialogThemeData(surfaceTintColor: Colors.white),
  floatingActionButtonTheme: FloatingActionButtonThemeData(shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(500))),
  bottomAppBarTheme: const BottomAppBarThemeData(
    surfaceTintColor: Colors.white, height: 60,
    padding: EdgeInsets.symmetric(vertical: 5),
  ),
  dividerTheme: DividerThemeData(color: const Color(0xFFBABFC4).withValues(alpha: 0.25), thickness: 0.5),
  tabBarTheme: const TabBarThemeData(dividerColor: Colors.transparent),
);
