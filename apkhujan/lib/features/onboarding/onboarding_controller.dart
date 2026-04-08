import 'package:flutter/material.dart';

class OnboardingSlideModel {
  final IconData icon;
  final String title;
  final String description;
  final Color color;

  OnboardingSlideModel({
    required this.icon,
    required this.title,
    required this.description,
    required this.color,
  });
}

class OnboardingController {
  static List<OnboardingSlideModel> slides = [
    OnboardingSlideModel(
      icon: Icons.cloud_rounded,
      title: 'Rainfall Monitoring\nSystem',
      description:
          'Welcome to the smart environmental monitoring solution.\nTrack rainfall, weather, and water levels in real-time.',
      color: const Color(0xFF42A5F5),
    ),
    OnboardingSlideModel(
      icon: Icons.sensors_rounded,
      title: 'Smart Sensor\nNetwork',
      description:
          'Monitor temperature, humidity, light intensity, and water levels with high-precision IoT sensors.',
      color: const Color(0xFF26C6DA),
    ),
    OnboardingSlideModel(
      icon: Icons.dashboard_rounded,
      title: 'Real-Time\nDashboard',
      description:
          'View live data streams, historical charts, and smart alerts — all from your fingertips.',
      color: const Color(0xFF66BB6A),
    ),
    OnboardingSlideModel(
      icon: Icons.solar_power_rounded,
      title: 'Solar-Powered\n& Sustainable',
      description:
          'Our system runs on solar energy. Monitor battery, charging status, and power usage in real-time.',
      color: const Color(0xFFFFB74D),
    ),
    OnboardingSlideModel(
      icon: Icons.rocket_launch_rounded,
      title: 'Ready to\nGet Started?',
      description:
          'Join us in monitoring the environment and managing resources more efficiently.',
      color: const Color(0xFF7C8CF8),
    ),
  ];
}
