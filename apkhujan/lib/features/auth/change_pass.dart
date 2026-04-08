import 'dart:math';
import 'dart:ui';
import 'package:flutter/material.dart';
import '../../core/services/api_service.dart';
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
// Change Password Page
// ──────────────────────────────────────────────
class ChangePassPage extends StatefulWidget {
  const ChangePassPage({super.key});

  @override
  State<ChangePassPage> createState() => _ChangePassPageState();
}

class _ChangePassPageState extends State<ChangePassPage> with TickerProviderStateMixin {
  bool _obscureOld = true;
  bool _obscureNew = true;
  bool _obscureConfirm = true;
  bool _isLoading = false;

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

    // Reset controllers
    AuthController.oldPasswordController.clear();
    AuthController.newPasswordController.clear();
    AuthController.confirmPasswordController.clear();

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

    _glowController = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 25),
    )..repeat();

    _fadeController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 800),
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
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final size = MediaQuery.of(context).size;

    return Scaffold(
      extendBodyBehindAppBar: true,
      appBar: AppBar(
        title: const Text('Keamanan', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w600)),
        backgroundColor: Colors.transparent,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, color: Colors.white, size: 20),
          onPressed: () => Navigator.pop(context),
        ),
      ),
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
                          _buildHeader(),
                          const SizedBox(height: 36),
                          _buildFormCard(),
                          const SizedBox(height: 40),
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
        Container(
          width: 80,
          height: 80,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            color: const Color(0xFF3D8BF5).withValues(alpha: 0.12),
            border: Border.all(
              color: const Color(0xFF3D8BF5).withValues(alpha: 0.25),
              width: 1.5,
            ),
          ),
          child: const Icon(
            Icons.lock_reset_rounded,
            size: 40,
            color: Color(0xFF5CA0F8),
          ),
        ),
        const SizedBox(height: 24),
        const Text(
          'Ganti Password',
          style: TextStyle(
            fontSize: 24,
            fontWeight: FontWeight.w700,
            color: Colors.white,
            letterSpacing: -0.5,
          ),
        ),
        const SizedBox(height: 8),
        Text(
          'Pastikan password baru Anda aman dan mudah diingat',
          textAlign: TextAlign.center,
          style: TextStyle(
            fontSize: 14,
            color: Colors.white.withValues(alpha: 0.6),
            fontWeight: FontWeight.w400,
          ),
        ),
      ],
    );
  }

  Widget _buildFormCard() {
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
            key: AuthController.changePassFormKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Atur Password Baru',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.w600,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 24),

                // ── Old Password ──
                _buildInputField(
                  controller: AuthController.oldPasswordController,
                  hint: 'Password Lama',
                  icon: Icons.lock_open_rounded,
                  obscure: _obscureOld,
                  suffixIcon: IconButton(
                    onPressed: () => setState(() => _obscureOld = !_obscureOld),
                    icon: Icon(
                      _obscureOld ? Icons.visibility_off_outlined : Icons.visibility_outlined,
                      color: Colors.white54,
                      size: 20,
                    ),
                  ),
                ),
                const SizedBox(height: 16),

                // ── New Password ──
                _buildInputField(
                  controller: AuthController.newPasswordController,
                  hint: 'Password Baru',
                  icon: Icons.lock_outline_rounded,
                  validator: AuthController.validatePassword,
                  obscure: _obscureNew,
                  suffixIcon: IconButton(
                    onPressed: () => setState(() => _obscureNew = !_obscureNew),
                    icon: Icon(
                      _obscureNew ? Icons.visibility_off_outlined : Icons.visibility_outlined,
                      color: Colors.white54,
                      size: 20,
                    ),
                  ),
                ),
                const SizedBox(height: 16),

                // ── Confirm New Password ──
                _buildInputField(
                  controller: AuthController.confirmPasswordController,
                  hint: 'Konfirmasi Password Baru',
                  icon: Icons.verified_user_outlined,
                  validator: (value) => AuthController.validateConfirmPassword(
                    value, 
                    AuthController.newPasswordController.text
                  ),
                  obscure: _obscureConfirm,
                  suffixIcon: IconButton(
                    onPressed: () => setState(() => _obscureConfirm = !_obscureConfirm),
                    icon: Icon(
                      _obscureConfirm ? Icons.visibility_off_outlined : Icons.visibility_outlined,
                      color: Colors.white54,
                      size: 20,
                    ),
                  ),
                ),
                const SizedBox(height: 32),

                // ── Submit Button ──
                _buildSubmitButton(),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildInputField({
    required TextEditingController controller,
    required String hint,
    required IconData icon,
    String? Function(String?)? validator,
    bool obscure = false,
    Widget? suffixIcon,
  }) {
    return TextFormField(
      controller: controller,
      validator: validator,
      obscureText: obscure,
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
        suffixIcon: suffixIcon,
        filled: true,
        fillColor: Colors.white.withValues(alpha: 0.08),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: BorderSide(color: Colors.white.withValues(alpha: 0.08), width: 1),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: BorderSide(color: const Color(0xFF3D8BF5).withValues(alpha: 0.5), width: 1.5),
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

  Widget _buildSubmitButton() {
    return SizedBox(
      width: double.infinity,
      height: 54,
      child: DecoratedBox(
        decoration: BoxDecoration(
          gradient: const LinearGradient(colors: [Color(0xFF3D8BF5), Color(0xFF5CA0F8)]),
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
          onPressed: _handleUpdate,
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.transparent,
            shadowColor: Colors.transparent,
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(27)),
          ),
          child: _isLoading
              ? const SizedBox(width: 24, height: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
              : const Text(
                  'Update Password',
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600, color: Colors.white),
                ),
        ),
      ),
    );
  }

  Future<void> _handleUpdate() async {
    if (!AuthController.changePassFormKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    final oldPass = AuthController.oldPasswordController.text;
    final newPass = AuthController.newPasswordController.text;
    final confirmPass = AuthController.confirmPasswordController.text;

    final result = await ApiService.changePassword(oldPass, newPass, confirmPass);

    if (!mounted) return;

    setState(() => _isLoading = false);

    if (result['success']) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(result['message']), backgroundColor: Colors.green),
      );
      // Wait a bit and pop
      Future.delayed(const Duration(seconds: 1), () => Navigator.pop(context));
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(result['message']), backgroundColor: Colors.redAccent),
      );
    }
  }
}
