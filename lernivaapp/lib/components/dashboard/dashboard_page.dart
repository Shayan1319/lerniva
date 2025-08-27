import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';
import 'dashboard_sidebar.dart';
import '../../pages/student_page.dart';

class DashboardPage extends StatefulWidget {
  const DashboardPage({super.key});

  @override
  State<DashboardPage> createState() => _DashboardPageState();
}

class _DashboardPageState extends State<DashboardPage> {
  bool _isSidebarOpen = false;

  void _toggleSidebar() {
    setState(() {
      _isSidebarOpen = !_isSidebarOpen;
    });
  }

  Future<void> _refreshData() async {
    await Future.delayed(const Duration(seconds: 1));
  }

  Widget _buildMainContent(BuildContext context) {
    final TextTheme textTheme = Theme.of(context).textTheme;

    return Container(
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          colors: [Color(0xFF1DA1F2), Color(0xFF794BC4), Color(0xFF17C3B2)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      child: RefreshIndicator(
        onRefresh: _refreshData,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          child: Padding(
            padding: const EdgeInsets.all(20.0),
            child: Column(
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(12),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.grey.withOpacity(0.2),
                        blurRadius: 8,
                        offset: const Offset(0, 4),
                      ),
                    ],
                  ),
                  child: LayoutBuilder(
                    builder: (context, constraints) {
                      return Row(
                        children: [
                          if (constraints.maxWidth <= 768)
                            IconButton(
                              icon: const Icon(Icons.menu_rounded, size: 28, color: Colors.blue),
                              onPressed: _toggleSidebar,
                            ),
                          Image.asset(
                            'assets/images/logo_dark.png',
                            height: 40,
                          ),
                          const SizedBox(width: 8),
                          Expanded(
                            child: Text(
                              'Dashboard',
                              style: textTheme.headlineSmall?.copyWith(
                                fontWeight: FontWeight.bold,
                                color: Colors.black87,
                              ),
                            ),
                          ),
                          IconButton(
                            icon: const Icon(Icons.notifications_active_outlined, color: Colors.blue),
                            onPressed: () {},
                            tooltip: "Notifications",
                          ),
                          IconButton(
                            icon: const Icon(Icons.arrow_back, color: Colors.blue),
                            onPressed: () {
                              Navigator.of(context).push(MaterialPageRoute(
                                  builder: (context) => StudentPage(
                                        toggleTheme: (val) {},
                                        themeMode: ThemeMode.system,
                                        toggleNotifications: (val) {},
                                        notificationsEnabled: false,
                                      )));
                            },
                            tooltip: "Back to Students",
                          ),
                        ],
                      );
                    },
                  ),
                ),
                const SizedBox(height: 24),
                Wrap(
                  spacing: 16,
                  runSpacing: 16,
                  children: const [
                    DashboardCard(title: 'Assignments/Tasks', icon: Icons.assignment, color: Colors.blue),
                    DashboardCard(title: 'Fee', icon: Icons.payment, color: Colors.deepPurple),
                    DashboardCard(title: 'Students', icon: Icons.people, color: Colors.teal),
                    DashboardCard(title: 'Teachers', icon: Icons.person, color: Colors.orange),
                    DashboardCard(title: 'Revenue', icon: Icons.attach_money, color: Colors.green),
                    DashboardCard(title: 'Attendance', icon: Icons.check_circle, color: Colors.indigo),
                    DashboardCard(title: 'Time Table', icon: Icons.schedule, color: Colors.pink),
                    DashboardCard(title: 'Email', icon: Icons.email, color: Colors.redAccent),
                    DashboardCard(title: 'Forms', icon: Icons.article, color: Colors.brown),
                    DashboardCard(title: 'Management', icon: Icons.manage_accounts, color: Colors.cyan),
                  ],
                ),
                const SizedBox(height: 40),
                Container(
                  width: double.infinity,
                  height: 300,
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(16),
                    boxShadow: const [
                      BoxShadow(
                        color: Colors.black12,
                        blurRadius: 10,
                        offset: Offset(0, 6),
                      ),
                    ],
                  ),
                  child: BarChart(
                    BarChartData(
                      barGroups: [
                        for (int i = 0; i < 5; i++)
                          BarChartGroupData(
                            x: i,
                            barRods: [
                              BarChartRodData(toY: (i + 1) * 10.0, color: Colors.blue),
                            ],
                          ),
                      ],
                      titlesData: FlTitlesData(
                        leftTitles: AxisTitles(sideTitles: SideTitles(showTitles: true)),
                        bottomTitles: AxisTitles(
                          sideTitles: SideTitles(
                            showTitles: true,
                            getTitlesWidget: (value, meta) => Padding(
                              padding: const EdgeInsets.only(top: 8),
                              child: Text('W${value.toInt() + 1}'),
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final bool isLargeScreen = MediaQuery.of(context).size.width > 768;
    return Scaffold(
      body: Stack(
        children: [
          Row(
            children: [
              if (isLargeScreen)
                const SizedBox(
                  width: 280,
                  child: DashboardSidebar(),
                ),
              Expanded(child: _buildMainContent(context)),
            ],
          ),

          // Full-screen overlay sidebar for mobile
          if (!isLargeScreen && _isSidebarOpen)
            Positioned.fill(
              child: Material(
                color: Colors.white,
                child: Column(
                  children: [
                    Container(
                      color: Colors.white,
                      padding: const EdgeInsets.only(left: 8, top: 40, bottom: 8),
                      alignment: Alignment.centerLeft,
                      child: IconButton(
                        icon: const Icon(Icons.arrow_back, color: Colors.blue),
                        onPressed: _toggleSidebar,
                      ),
                    ),
                    const Expanded(child: DashboardSidebar()),
                  ],
                ),
              ),
            ),
        ],
      ),
    );
  }
}

class DashboardCard extends StatelessWidget {
  final String title;
  final IconData icon;
  final Color color;

  const DashboardCard({
    super.key,
    required this.title,
    required this.icon,
    required this.color,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 160,
      height: 140,
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: const [
          BoxShadow(
            color: Colors.black12,
            blurRadius: 8,
            offset: Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(icon, size: 40, color: color),
          const SizedBox(height: 12),
          Text(
            title,
            textAlign: TextAlign.center,
            style: const TextStyle(
              fontWeight: FontWeight.w600,
              fontSize: 16,
            ),
          ),
        ],
      ),
    );
  }
}
