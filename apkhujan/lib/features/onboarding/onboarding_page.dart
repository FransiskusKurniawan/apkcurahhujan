import 'dart:math';
import 'dart:ui';
import 'package:flutter/material.dart';
import 'onboarding_controller.dart';
import '../auth/login_page.dart';

// ──────────────────────────────────────────────
// Floating particle model
// ──────────────────────────────────────────────
class _Particle {
  double x;
  double y;
  double radius;
  double speed;
  double opacity;

  _Particle({
    required this.x,
    required this.y,
    required this.radius,
    required this.speed,
    required this.opacity,
  });
}

// ──────────────────────────────────────────────
// Custom painter for floating particles
// ──────────────────────────────────────────────
class _FloatingParticlesPainter extends CustomPainter {
  final double animationValue;
  final List<_Particle> particles;

  _FloatingParticlesPainter({
    required this.animationValue,
    required this.particles,
  });

  @override
  void paint(Canvas canvas, Size size) {
    for (var p in particles) {
      final dy = (p.y - animationValue * p.speed * size.height) % size.height;
      final dx = p.x * size.width + sin(animationValue * 2 * pi + p.y) * 15;
      final paint = Paint()
        ..color = Colors.white.withValues(alpha: p.opacity * 0.25)
        ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 2);
      canvas.drawCircle(Offset(dx, dy), p.radius, paint);
    }
  }

  @override
  bool shouldRepaint(covariant _FloatingParticlesPainter old) => true;
}

// ──────────────────────────────────────────────
// Custom painter for atmospheric background glows
// ──────────────────────────────────────────────
class _BackgroundGlowPainter extends CustomPainter {
  final double animationValue;

  _BackgroundGlowPainter({required this.animationValue});

  @override
  void paint(Canvas canvas, Size size) {
    final center1 = Offset(
      size.width * (0.2 + 0.1 * sin(animationValue * 2 * pi)),
      size.height * (0.3 + 0.1 * cos(animationValue * 2 * pi)),
    );
    final center2 = Offset(
      size.width * (0.8 + 0.1 * cos(animationValue * 2 * pi)),
      size.height * (0.7 + 0.1 * sin(animationValue * 2 * pi)),
    );

    final paint1 = Paint()
      ..color = const Color(0xFF3D8BF5).withValues(alpha: 0.08)
      ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 50);

    final paint2 = Paint()
      ..color = const Color(0xFF5CA0F8).withValues(alpha: 0.06)
      ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 70);

    canvas.drawCircle(center1, size.width * 0.4, paint1);
    canvas.drawCircle(center2, size.width * 0.5, paint2);
  }

  @override
  bool shouldRepaint(covariant _BackgroundGlowPainter old) => true;
}

// ──────────────────────────────────────────────
// Main Onboarding Page
// ──────────────────────────────────────────────
class OnboardingPage extends StatefulWidget {
  const OnboardingPage({super.key});

  @override
  State<OnboardingPage> createState() => _OnboardingPageState();
}

class _OnboardingPageState extends State<OnboardingPage>
    with TickerProviderStateMixin {
  final PageController _pageController = PageController();
  int _currentPage = 0;

  // Animation controllers
  late AnimationController _particleController;
  late AnimationController _glowController;
  late AnimationController _contentController;

  // Particles
  final List<_Particle> _particles = [];
  final Random _random = Random();

  // ── Unified background gradient ──
  static const List<Color> _backgroundGradient = [
    Color(0xFF0A1628),
    Color(0xFF112240),
    Color(0xFF1A3158),
  ];

  @override
  void initState() {
    super.initState();

    // Generate random particles
    for (int i = 0; i < 25; i++) {
      _particles.add(
        _Particle(
          x: _random.nextDouble(),
          y: _random.nextDouble() * 2,
          radius: _random.nextDouble() * 2.5 + 0.8,
          speed: _random.nextDouble() * 0.4 + 0.2,
          opacity: _random.nextDouble() * 0.5 + 0.15,
        ),
      );
    }

    // Particle animation — continuous loop
    _particleController = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 15),
    )..repeat();

    // Glow animation — very slow loop
    _glowController = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 25),
    )..repeat();

    // Content fade-in on slide change
    _contentController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 500),
    )..forward();
  }

  @override
  void dispose() {
    _pageController.dispose();
    _particleController.dispose();
    _glowController.dispose();
    _contentController.dispose();
    super.dispose();
  }

  void _onPageChanged(int index) {
    setState(() {
      _currentPage = index;
    });
    _contentController.reset();
    _contentController.forward();
  }

  void _nextPage() {
    if (_currentPage < OnboardingController.slides.length - 1) {
      _pageController.nextPage(
        duration: const Duration(milliseconds: 500),
        curve: Curves.easeInOutCubic,
      );
    } else {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (context) => const LoginPage()),
      );
    }
  }

  void _skip() {
    _pageController.animateToPage(
      OnboardingController.slides.length - 1,
      duration: const Duration(milliseconds: 800),
      curve: Curves.easeInOutCubic,
    );
  }

  @override
  Widget build(BuildContext context) {
    final slides = OnboardingController.slides;
    final isLastPage = _currentPage == slides.length - 1;

    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: _backgroundGradient,
          ),
        ),
        child: Stack(
          children: [
            // ── Atmospheric glows ──
            AnimatedBuilder(
              animation: _glowController,
              builder: (context, child) {
                return CustomPaint(
                  size: MediaQuery.of(context).size,
                  painter: _BackgroundGlowPainter(
                    animationValue: _glowController.value,
                  ),
                );
              },
            ),

            // ── Floating particles ──
            AnimatedBuilder(
              animation: _particleController,
              builder: (context, child) {
                return CustomPaint(
                  size: MediaQuery.of(context).size,
                  painter: _FloatingParticlesPainter(
                    animationValue: _particleController.value,
                    particles: _particles,
                  ),
                );
              },
            ),

            // ── Main content ──
            SafeArea(
              child: Column(
                children: [
                  // Skip button
                  _buildSkipButton(isLastPage),

                  // Slides
                  Expanded(
                    child: PageView.builder(
                      controller: _pageController,
                      onPageChanged: _onPageChanged,
                      itemCount: slides.length,
                      itemBuilder: (context, index) {
                        return _AnimatedSlide(
                          model: slides[index],
                          contentController: _contentController,
                          isActive: index == _currentPage,
                        );
                      },
                    ),
                  ),

                  // Bottom section: dots + button
                  _buildBottomSection(isLastPage),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  // ── Skip button ──
  Widget _buildSkipButton(bool isLastPage) {
    return Align(
      alignment: Alignment.topRight,
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        child: AnimatedOpacity(
          opacity: isLastPage ? 0.0 : 1.0,
          duration: const Duration(milliseconds: 300),
          child: TextButton(
            onPressed: isLastPage ? null : _skip,
            style: TextButton.styleFrom(
              foregroundColor: Colors.white.withValues(alpha: 0.7),
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
            ),
            child: const Text(
              'Skip',
              style: TextStyle(
                fontSize: 15,
                fontWeight: FontWeight.w500,
                letterSpacing: 0.3,
              ),
            ),
          ),
        ),
      ),
    );
  }

  // ── Bottom section: dots + button ──
  Widget _buildBottomSection(bool isLastPage) {
    final slides = OnboardingController.slides;

    return Padding(
      padding: const EdgeInsets.fromLTRB(32, 0, 32, 40),
      child: Column(
        children: [
          // ── Progress dots ──
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: List.generate(slides.length, (index) => _buildDot(index)),
          ),
          const SizedBox(height: 36),

          // ── Consistent accent button ──
          SizedBox(
            width: double.infinity,
            height: 54,
            child: DecoratedBox(
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFF3D8BF5), Color(0xFF5CA0F8)],
                ),
                borderRadius: BorderRadius.circular(27),
                boxShadow: [
                  BoxShadow(
                    color: const Color(0xFF3D8BF5).withValues(alpha: 0.35),
                    blurRadius: 18,
                    offset: const Offset(0, 6),
                  ),
                ],
              ),
              child: ElevatedButton(
                onPressed: _nextPage,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.transparent,
                  shadowColor: Colors.transparent,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(27),
                  ),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      isLastPage ? 'Get Started' : 'Next',
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w600,
                        color: Colors.white,
                        letterSpacing: 0.3,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Icon(
                      isLastPage
                          ? Icons.arrow_forward_rounded
                          : Icons.arrow_forward_rounded,
                      color: Colors.white,
                      size: 20,
                    ),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  // ── Single progress dot ──
  Widget _buildDot(int index) {
    bool isActive = _currentPage == index;
    return AnimatedContainer(
      duration: const Duration(milliseconds: 350),
      curve: Curves.easeOutCubic,
      margin: const EdgeInsets.only(right: 8),
      height: 6,
      width: isActive ? 28 : 6,
      decoration: BoxDecoration(
        color: isActive ? Colors.white : Colors.white.withValues(alpha: 0.25),
        borderRadius: BorderRadius.circular(3),
      ),
    );
  }
}

// ──────────────────────────────────────────────
// Animated Slide Widget (simplified)
// ──────────────────────────────────────────────
class _AnimatedSlide extends StatelessWidget {
  final OnboardingSlideModel model;
  final AnimationController contentController;
  final bool isActive;

  const _AnimatedSlide({
    required this.model,
    required this.contentController,
    required this.isActive,
  });

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: contentController,
      builder: (context, child) {
        final fadeValue = CurvedAnimation(
          parent: contentController,
          curve: Curves.easeOut,
        ).value;
        final slideValue = CurvedAnimation(
          parent: contentController,
          curve: Curves.easeOutCubic,
        ).value;

        return Opacity(
          opacity: fadeValue,
          child: Transform.translate(
            offset: Offset(0, 24 * (1 - slideValue)),
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 36),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  // ── Icon with subtle glow ──
                  _buildIconArea(),
                  const SizedBox(height: 48),

                  // ── Title ──
                  Text(
                    model.title,
                    textAlign: TextAlign.center,
                    style: const TextStyle(
                      fontSize: 30,
                      fontWeight: FontWeight.w700,
                      color: Colors.white,
                      height: 1.25,
                      letterSpacing: -0.3,
                    ),
                  ),
                  const SizedBox(height: 18),

                  // ── Description ──
                  Text(
                    model.description,
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      fontSize: 15,
                      color: Colors.white.withValues(alpha: 0.7),
                      height: 1.6,
                      fontWeight: FontWeight.w400,
                    ),
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );
  }

  Widget _buildIconArea() {
    return Container(
      width: 120,
      height: 120,
      decoration: BoxDecoration(
        shape: BoxShape.circle,
        color: model.color.withValues(alpha: 0.12),
        border: Border.all(
          color: model.color.withValues(alpha: 0.25),
          width: 1.5,
        ),
        boxShadow: [
          BoxShadow(
            color: model.color.withValues(alpha: 0.2),
            blurRadius: 30,
            spreadRadius: 2,
          ),
        ],
      ),
      child: Icon(
        model.icon,
        size: 52,
        color: model.color.withValues(alpha: 0.9),
      ),
    );
  }
}
