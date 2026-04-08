import 'dart:math';
import 'dart:ui';
import 'package:flutter/material.dart';
import 'auth_controller.dart';

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
      ..color = const Color(0xFF3D8BF5).withValues(alpha: 0.1)
      ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 50);

    final paint2 = Paint()
      ..color = const Color(0xFF5CA0F8).withValues(alpha: 0.08)
      ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 70);

    canvas.drawCircle(center1, size.width * 0.4, paint1);
    canvas.drawCircle(center2, size.width * 0.5, paint2);
  }

  @override
  bool shouldRepaint(covariant _BackgroundGlowPainter old) => true;
}

// ──────────────────────────────────────────────
// Forgot Password Page
// ──────────────────────────────────────────────
class ForgotPage extends StatefulWidget {
  const ForgotPage({super.key});

  @override
  State<ForgotPage> createState() => _ForgotPageState();
}

class _ForgotPageState extends State<ForgotPage> with TickerProviderStateMixin {
  final GlobalKey<FormState> _forgotFormKey = GlobalKey<FormState>();
  final TextEditingController _emailController = TextEditingController();

  // Animation controllers
  late AnimationController _particleController;
  late AnimationController _glowController;
  late AnimationController _fadeController;
  late Animation<double> _fadeAnim;
  late Animation<Offset> _slideAnim;

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

    _particleController = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 15),
    )..repeat();

    // Glow animation — very slow loop
    _glowController = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 25),
    )..repeat();

    // Fade-in animation for the form
    _fadeController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1000),
    );
    _fadeAnim = CurvedAnimation(parent: _fadeController, curve: Curves.easeOut);
    _slideAnim = Tween<Offset>(begin: const Offset(0, 0.05), end: Offset.zero)
        .animate(
          CurvedAnimation(parent: _fadeController, curve: Curves.easeOutCubic),
        );

    _fadeController.forward();
  }

  @override
  void dispose() {
    _particleController.dispose();
    _glowController.dispose();
    _fadeController.dispose();
    _emailController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final size = MediaQuery.of(context).size;

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
                  size: size,
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
                  size: size,
                  painter: _FloatingParticlesPainter(
                    animationValue: _particleController.value,
                    particles: _particles,
                  ),
                );
              },
            ),

            // ── Main content ──
            SafeArea(
              child: Center(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.symmetric(horizontal: 28),
                  child: FadeTransition(
                    opacity: _fadeAnim,
                    child: SlideTransition(
                      position: _slideAnim,
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const SizedBox(height: 20),
                          _buildHeader(),
                          const SizedBox(height: 36),
                          _buildForgotCard(),
                          const SizedBox(height: 24),
                          _buildLoginRow(),
                          const SizedBox(height: 20),
                        ],
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHeader() {
    return Column(
      children: [
        // ── Icon with subtle glow ──
        Container(
          width: 100,
          height: 100,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            color: const Color(0xFF3D8BF5).withValues(alpha: 0.12),
            border: Border.all(
              color: const Color(0xFF3D8BF5).withValues(alpha: 0.25),
              width: 1.5,
            ),
            boxShadow: [
              BoxShadow(
                color: const Color(0xFF3D8BF5).withValues(alpha: 0.2),
                blurRadius: 30,
                spreadRadius: 2,
              ),
            ],
          ),
          child: const Icon(
            Icons.cloud_rounded,
            size: 48,
            color: Color(0xFF5CA0F8),
          ),
        ),
        const SizedBox(height: 32),
        const Text(
          'Rainfall Monitoring',
          style: TextStyle(
            fontSize: 28,
            fontWeight: FontWeight.w700,
            color: Colors.white,
            letterSpacing: -0.5,
            height: 1.2,
          ),
        ),
        const Text(
          'System',
          style: TextStyle(
            fontSize: 28,
            fontWeight: FontWeight.w700,
            color: Colors.white,
            letterSpacing: -0.5,
            height: 1.2,
          ),
        ),
        const SizedBox(height: 12),
        Text(
          'Recover your account',
          style: TextStyle(
            fontSize: 14,
            color: Colors.white.withValues(alpha: 0.6),
            fontWeight: FontWeight.w400,
            letterSpacing: 0.3,
          ),
        ),
      ],
    );
  }

  Widget _buildForgotCard() {
    return ClipRRect(
      borderRadius: BorderRadius.circular(24),
      child: BackdropFilter(
        filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
        child: Container(
          padding: const EdgeInsets.all(28),
          decoration: BoxDecoration(
            color: Colors.white.withValues(alpha: 0.05),
            borderRadius: BorderRadius.circular(24),
            border: Border.all(
              color: Colors.white.withValues(alpha: 0.1),
              width: 1,
            ),
          ),
          child: Form(
            key: _forgotFormKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Forgot Password',
                  style: TextStyle(
                    fontSize: 22,
                    fontWeight: FontWeight.w600,
                    color: Colors.white,
                    letterSpacing: -0.2,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  'Enter your email address and we will send you a link to reset your password.',
                  style: TextStyle(
                    fontSize: 14,
                    color: Colors.white.withValues(alpha: 0.6),
                  ),
                ),
                const SizedBox(height: 28),

                // ── Email field ──
                _buildInputField(
                  controller: _emailController,
                  hint: 'Email address',
                  icon: Icons.email_outlined,
                  validator: AuthController.validateEmail,
                  keyboardType: TextInputType.emailAddress,
                ),
                const SizedBox(height: 32),

                // ── Reset button ──
                _buildResetButton(),
              ],
            ),
          ),
        ),
      ),
    );
  }

  // ── Reusable input field ──
  Widget _buildInputField({
    required TextEditingController controller,
    required String hint,
    required IconData icon,
    String? Function(String?)? validator,
    TextInputType? keyboardType,
  }) {
    return TextFormField(
      controller: controller,
      validator: validator,
      keyboardType: keyboardType,
      style: const TextStyle(color: Colors.white, fontSize: 15),
      decoration: InputDecoration(
        hintText: hint,
        hintStyle: TextStyle(
          color: Colors.white.withValues(alpha: 0.4),
          fontSize: 14,
        ),
        prefixIcon: Padding(
          padding: const EdgeInsets.only(left: 16, right: 12),
          child: Icon(icon, color: Colors.white54, size: 20),
        ),
        prefixIconConstraints: const BoxConstraints(minWidth: 0, minHeight: 0),
        filled: true,
        fillColor: Colors.white.withValues(alpha: 0.08),
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 16,
          vertical: 16,
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: BorderSide(
            color: Colors.white.withValues(alpha: 0.08),
            width: 1,
          ),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: BorderSide(
            color: const Color(0xFF3D8BF5).withValues(alpha: 0.5),
            width: 1.5,
          ),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFEF5350), width: 1),
        ),
        focusedErrorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFEF5350), width: 1.5),
        ),
        errorStyle: const TextStyle(fontSize: 12),
      ),
    );
  }

  Widget _buildResetButton() {
    return SizedBox(
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
          onPressed: _handleReset,
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.transparent,
            shadowColor: Colors.transparent,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(27),
            ),
          ),
          child: const Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text(
                'Send Reset Link',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w600,
                  color: Colors.white,
                  letterSpacing: 0.3,
                ),
              ),
              SizedBox(width: 8),
              Icon(Icons.mark_email_read_rounded, color: Colors.white, size: 20),
            ],
          ),
        ),
      ),
    );
  }

  // ── Log In row ──
  Widget _buildLoginRow() {
    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Text(
          "Remember your password? ",
          style: TextStyle(
            fontSize: 14,
            color: Colors.white.withValues(alpha: 0.6),
          ),
        ),
        GestureDetector(
          onTap: () {
            // Navigate back to Login page
            Navigator.pop(context);
          },
          child: const Text(
            'Log In',
            style: TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.w600,
              color: Color(0xFF5CA0F8),
            ),
          ),
        ),
      ],
    );
  }

  // ── Handle reset ──
  void _handleReset() {
    if (_forgotFormKey.currentState!.validate()) {
      // TODO: implement reset password API call
    }
  }
}
