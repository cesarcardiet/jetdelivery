import 'package:stackfood_multivendor/util/app_constants.dart';
import 'package:stackfood_multivendor/util/app_theme_colors.dart';
import 'package:flutter/material.dart';

ThemeData dark = ThemeData(
  useMaterial3: true,
  fontFamily: AppConstants.fontFamily,
  primaryColor: AppThemeColors.primary,
  secondaryHeaderColor: AppThemeColors.primaryMuted,
  highlightColor: AppThemeColors.primary.withValues(alpha: 0.12),
  splashColor: AppThemeColors.primary.withValues(alpha: 0.12),
  disabledColor: const Color(0xffa2a7ad),
  brightness: Brightness.dark,
  hintColor: const Color(0xFF5E6472),
  cardColor: const Color(0xFF141313),
  shadowColor: Colors.white.withValues(alpha: 0.03),
  progressIndicatorTheme: const ProgressIndicatorThemeData(color: AppThemeColors.primary),
  textButtonTheme: TextButtonThemeData(style: TextButton.styleFrom(foregroundColor: AppThemeColors.primary)),
  filledButtonTheme: FilledButtonThemeData(
    style: FilledButton.styleFrom(backgroundColor: AppThemeColors.primary, foregroundColor: Colors.white),
  ),
  elevatedButtonTheme: ElevatedButtonThemeData(
    style: ElevatedButton.styleFrom(backgroundColor: AppThemeColors.primary, foregroundColor: Colors.white),
  ),
  colorScheme: const ColorScheme.dark(primary: AppThemeColors.primary,
    tertiary: Color(0xff6165D7),
    tertiaryContainer: Color(0xff171DB6),
    secondary: AppThemeColors.primaryMuted).copyWith(surface: const Color(0xFF272727)).copyWith(error: const Color(0xFFdd3135),
  ),
  popupMenuTheme: const PopupMenuThemeData(color: Color(0xFF29292D), surfaceTintColor: Color(0xFF29292D)),
  dialogTheme: const DialogThemeData(surfaceTintColor: Colors.white10),
  floatingActionButtonTheme: FloatingActionButtonThemeData(shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(500))),
  bottomAppBarTheme: const BottomAppBarThemeData(
    surfaceTintColor: Colors.black, height: 60,
    padding: EdgeInsets.symmetric(vertical: 5),
  ),
  dividerTheme: DividerThemeData(color: const Color(0xffa2a7ad).withValues(alpha: 0.25), thickness: 0.5),
  tabBarTheme: const TabBarThemeData(dividerColor: Colors.transparent),
);
