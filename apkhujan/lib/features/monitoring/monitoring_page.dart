import 'dart:ui';
import 'dart:convert';
import 'package:flutter/material.dart';
import 'dart:developer' as developer;
import '../../core/services/api_service.dart';
import '../../core/services/websocket_service.dart';
import '../auth/login_page.dart';
import '../auth/change_pass.dart';

class MonitoringPage extends StatefulWidget {
  const MonitoringPage({super.key});

  @override
  State<MonitoringPage> createState() => _MonitoringPageState();
}

class _MonitoringPageState extends State<MonitoringPage> {
  bool _isLoading = true;
  Map<String, dynamic>? _sensorData;
  Map<String, String?> _userData = {};
  String _errorMessage = '';

  // ── Unified background gradient ──
  static const List<Color> _backgroundGradient = [
    Color(0xFF0A1628),
    Color(0xFF112240),
    Color(0xFF1A3158),
  ];

  @override
  void initState() {
    super.initState();
    _fetchUserData();
    _fetchData();
    _setupWebsocketListener();
  }

  void _setupWebsocketListener() async {
    final service = WebsocketService();
    await service.subscribe('sensor-data');
    
    service.addListener((PusherEvent event) {
      if (event.channelName == 'sensor-data' && event.eventName == 'new-data') {
        developer.log('New data received via WebSocket: ${event.data}');
        try {
          final decoded = jsonDecode(event.data.toString());
          if (mounted) {
            setState(() {
              // Laravel serializes public property 'data' from our Event
              _sensorData = decoded['data'] ?? decoded;
            });
          }
        } catch (e) {
          developer.log('Error decoding websocket data: $e');
        }
      }
    });
  }

  Future<void> _fetchUserData() async {
    final userData = await ApiService.getUserData();
    if (mounted) {
      setState(() {
        _userData = userData;
      });
    }
  }

  @override
  void dispose() {
    super.dispose();
  }

  Future<void> _fetchData({bool showLoadingIndicator = true}) async {
    if (showLoadingIndicator) {
      setState(() => _isLoading = true);
    }
    
    final result = await ApiService.getSensorData();
    
    if (mounted) {
      setState(() {
        if (showLoadingIndicator) _isLoading = false;
        
        if (result['success']) {
          _sensorData = result['data'];
          _errorMessage = '';
        } else {
          _errorMessage = result['message'];
        }
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      extendBodyBehindAppBar: true,
      appBar: AppBar(
        title: const Text('Dashboard', style: TextStyle(fontWeight: FontWeight.w600, color: Colors.white)),
        backgroundColor: Colors.transparent,
        elevation: 0,
        centerTitle: true,
        actions: [
          Builder(
            builder: (context) => IconButton(
              icon: const Icon(Icons.person, color: Colors.white),
              onPressed: () => Scaffold.of(context).openEndDrawer(),
            ),
          ),
        ],
      ),
      endDrawer: _buildEndDrawer(),
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: _backgroundGradient,
          ),
        ),
        child: SafeArea(
          child: _isLoading 
              ? const Center(child: CircularProgressIndicator(color: Color(0xFF3D8BF5)))
              : _errorMessage.isNotEmpty
                  ? Center(
                      child: Text(
                        _errorMessage,
                        style: const TextStyle(color: Colors.redAccent, fontSize: 16),
                      ),
                    )
                  : _sensorData == null
                      ? const Center(
                          child: Text(
                            'No data available yet.',
                            style: TextStyle(color: Colors.white70, fontSize: 16),
                          ),
                        )
                      : _buildDashboard(),
        ),
      ),
    );
  }

  Widget _buildEndDrawer() {
    final name = _userData['name'] ?? 'User';
    final username = _userData['username'] ?? '@user';

    return Drawer(
      backgroundColor: const Color(0xFF0A1628), // Matches gradient start
      child: Column(
        children: [
          UserAccountsDrawerHeader(
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                colors: [Color(0xFF3D8BF5), Color(0xFF5CA0F8)],
              ),
            ),
            accountName: Text(
              name,
              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 18),
            ),
            accountEmail: Text(username),
            currentAccountPicture: CircleAvatar(
              backgroundColor: Colors.white,
              child: Text(
                name.isNotEmpty ? name[0].toUpperCase() : 'U',
                style: const TextStyle(fontSize: 24, color: Color(0xFF3D8BF5), fontWeight: FontWeight.bold),
              ),
            ),
          ),
          ListTile(
            leading: const Icon(Icons.dashboard, color: Colors.white),
            title: const Text('Dashboard', style: TextStyle(color: Colors.white)),
            onTap: () => Navigator.pop(context),
          ),
          ListTile(
            leading: const Icon(Icons.lock_reset_rounded, color: Colors.white),
            title: const Text('Change Password', style: TextStyle(color: Colors.white)),
            onTap: () {
              Navigator.pop(context); // Close drawer
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => const ChangePassPage()),
              );
            },
          ),
          const Divider(color: Colors.white24),
          ListTile(
            leading: const Icon(Icons.logout, color: Colors.redAccent),
            title: const Text('Logout', style: TextStyle(color: Colors.redAccent)),
            onTap: () async {
              await ApiService.removeToken();
              if (mounted) {
                Navigator.pushAndRemoveUntil(
                  context,
                  MaterialPageRoute(builder: (context) => const LoginPage()),
                  (route) => false,
                );
              }
            },
          ),
        ],
      ),
    );
  }

  Widget _buildDashboard() {
    return RefreshIndicator(
      onRefresh: () => _fetchData(),
      color: const Color(0xFF3D8BF5),
      child: ListView(
        padding: const EdgeInsets.all(20),
        physics: const AlwaysScrollableScrollPhysics(),
        children: [
          // ── Header Status ──
          _buildSystemStatus(),
          const SizedBox(height: 24),
          
          // ── Environment Metrics ──
          const Text(
            'Environment Metrics',
            style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(child: _buildMetricCard('Temperature', '${_sensorData!['temperature'] ?? 0}°C', Icons.thermostat, Colors.orangeAccent)),
              const SizedBox(width: 12),
              Expanded(child: _buildMetricCard('Humidity', '${_sensorData!['humidity'] ?? 0}%', Icons.water_drop, Colors.lightBlue)),
            ],
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(child: _buildMetricCard('Rainfall', '${_sensorData!['rainfall'] ?? 0} mm', Icons.grain, Colors.blueAccent)),
              const SizedBox(width: 12),
              Expanded(child: _buildMetricCard('Light (Lux)', '${_sensorData!['lux'] ?? 0}', Icons.wb_sunny, Colors.yellow)),
            ],
          ),
          const SizedBox(height: 24),

          // ── Water Level & Pump ──
          const Text(
            'Water Management',
            style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 12),
          _buildWaterLevelCard(),
          const SizedBox(height: 12),
          _buildPumpStatusCard(),
          const SizedBox(height: 24),

          // ── Power System (Solar & Battery) ──
          const Text(
            'Power System',
            style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 12),
          _buildPowerMetrics(),
          const SizedBox(height: 32),
        ],
      ),
    );
  }

  Widget _buildSystemStatus() {
    final status = _sensorData!['status'] ?? 'Unknown';
    final time = _sensorData!['timertc'] ?? 'N/A';
    
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white.withValues(alpha: 0.05),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.white.withValues(alpha: 0.1), width: 1),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('System Status', style: TextStyle(color: Colors.white54, fontSize: 12)),
              const SizedBox(height: 4),
              Row(
                children: [
                  Container(
                    width: 10,
                    height: 10,
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      color: status.toString().toLowerCase() == 'normal' ? Colors.greenAccent : Colors.amber,
                      boxShadow: [
                        BoxShadow(
                          color: (status.toString().toLowerCase() == 'normal' ? Colors.green : Colors.amber).withValues(alpha: 0.5),
                          blurRadius: 8,
                          spreadRadius: 2,
                        )
                      ],
                    ),
                  ),
                  const SizedBox(width: 8),
                  Text(
                    status.toString().toUpperCase(),
                    style: const TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold),
                  ),
                ],
              ),
            ],
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              const Text('Last Updated', style: TextStyle(color: Colors.white54, fontSize: 12)),
              const SizedBox(height: 4),
              Text(
                time.toString(),
                style: const TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.w600),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildMetricCard(String title, String value, IconData icon, Color color) {
    return ClipRRect(
      borderRadius: BorderRadius.circular(20),
      child: BackdropFilter(
        filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
        child: Container(
          padding: const EdgeInsets.all(20),
          decoration: BoxDecoration(
            color: Colors.white.withValues(alpha: 0.05),
            borderRadius: BorderRadius.circular(20),
            border: Border.all(color: Colors.white.withValues(alpha: 0.08)),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Icon(icon, color: color, size: 28),
              const SizedBox(height: 16),
              Text(title, style: TextStyle(color: Colors.white.withValues(alpha: 0.6), fontSize: 13)),
              const SizedBox(height: 4),
              Text(
                value,
                style: const TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.bold),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildWaterLevelCard() {
    final level = _sensorData!['water_level']?.toString() ?? '0';
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white.withValues(alpha: 0.05),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.blueAccent.withValues(alpha: 0.3)),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: Colors.blueAccent.withValues(alpha: 0.2),
                  shape: BoxShape.circle,
                ),
                child: const Icon(Icons.waves, color: Colors.blueAccent),
              ),
              const SizedBox(width: 16),
              const Text('Water Level', style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.w500)),
            ],
          ),
          Text(
            '$level cm',
            style: const TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold),
          ),
        ],
      ),
    );
  }

  Widget _buildPumpStatusCard() {
    final pump = _sensorData!['status_pompa']?.toString() ?? 'OFF';
    final isOn = pump.toUpperCase() == 'ON';
    
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white.withValues(alpha: 0.05),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: (isOn ? Colors.green : Colors.redAccent).withValues(alpha: 0.3)),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: (isOn ? Colors.green : Colors.redAccent).withValues(alpha: 0.2),
                  shape: BoxShape.circle,
                ),
                child: Icon(isOn ? Icons.power : Icons.power_off, color: isOn ? Colors.greenAccent : Colors.redAccent),
              ),
              const SizedBox(width: 16),
              const Text('Pump Status', style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.w500)),
            ],
          ),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
            decoration: BoxDecoration(
              color: (isOn ? Colors.green : Colors.redAccent).withValues(alpha: 0.2),
              borderRadius: BorderRadius.circular(20),
            ),
            child: Text(
              isOn ? 'ON' : 'OFF',
              style: TextStyle(
                color: isOn ? Colors.greenAccent : Colors.redAccent,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPowerMetrics() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white.withValues(alpha: 0.05),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.white.withValues(alpha: 0.08)),
      ),
      child: Column(
        children: [
          _buildPowerRow('Solar Panel Voltage', '${_sensorData!['voltage_panel'] ?? 0} V', Icons.solar_power, Colors.amber),
          const Divider(color: Colors.white12, height: 32),
          _buildPowerRow('Solar Panel Current', '${_sensorData!['current_panel'] ?? 0} A', Icons.electric_bolt, Colors.amber),
          const Divider(color: Colors.white12, height: 32),
          _buildPowerRow('Battery Voltage', '${_sensorData!['voltage_baterai'] ?? 0} V', Icons.battery_charging_full, Colors.greenAccent),
          const Divider(color: Colors.white12, height: 32),
          _buildPowerRow('Battery Current', '${_sensorData!['current_baterai'] ?? 0} A', Icons.bolt, Colors.greenAccent),
        ],
      ),
    );
  }

  Widget _buildPowerRow(String label, String value, IconData icon, Color color) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Row(
          children: [
            Icon(icon, color: color, size: 20),
            const SizedBox(width: 12),
            Text(label, style: const TextStyle(color: Colors.white70, fontSize: 14)),
          ],
        ),
        Text(value, style: const TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold)),
      ],
    );
  }
}
