import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';

import 'chat_support_page.dart';
import 'feedback_page.dart';
import 'settings_page.dart';
import 'notifications_page.dart';
import 'calendar_page.dart';
import 'profile_page.dart';
import 'leave_page.dart';
import '../components/Analytic_Task_Detail/task_details_page.dart';
import '../components/subjects_page.dart';

class StudentPage extends StatefulWidget {
  final void Function(bool) toggleTheme;
  final ThemeMode themeMode;
  final void Function(bool) toggleNotifications;
  final bool notificationsEnabled;

  const StudentPage({
    super.key,
    required this.toggleTheme,
    required this.themeMode,
    required this.toggleNotifications,
    required this.notificationsEnabled,
  });

  @override
  State<StudentPage> createState() => _StudentPageState();
}

class _StudentPageState extends State<StudentPage> {
  static const Color lurnivaBlue = Color(0xFF3B38FF);

  /// --- DYNAMIC DATA ---
  final Map<String, double> attendanceData = {
    "Chem": 78,
    "Bio": 92,
    "Eng": 85,
    "Urdu": 66,
    "Phy": 74,
    "IS": 88,
    "Math": 95,
    "Pak": 81,
  };

  /// 👇 Subjects with tasks (Assignment/Quiz/Task)
  final Set<String> taskSubjects = {"Math", "Eng"}; // demo example

  double get overallAttendance =>
      attendanceData.isEmpty
          ? 0
          : attendanceData.values.reduce((a, b) => a + b) / attendanceData.length;

  final double overallGrade = 82;

  final List<Map<String, dynamic>> categories = [
    {'title': 'Subjects', 'icon': Icons.book, 'color': const Color(0xFF4D91F2)},
    {'title': 'Attendance', 'icon': Icons.check_circle, 'color': const Color(0xFF29B6B6)},
    {'title': 'Grades', 'icon': Icons.star, 'color': const Color(0xFFFFC107)},
    {'title': 'Assignments', 'icon': Icons.assignment, 'color': const Color(0xFF8E24AA)},
    {'title': 'Timetable', 'icon': Icons.access_time, 'color': const Color(0xFF26A69A)},
    {'title': 'Calendar', 'icon': Icons.calendar_today, 'color': const Color(0xFF42A5F5)},
    {'title': 'Announcements', 'icon': Icons.notification_important, 'color': const Color(0xFFFF7043)},
    {'title': 'Fees', 'icon': Icons.credit_card, 'color': const Color(0xFFEF5350)},
    {'title': 'Leave', 'icon': Icons.note_alt, 'color': const Color(0xFF43A047)},
  ];

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [Color(0xFF1DA1F2), Color(0xFF794BC4), Color(0xFF17C3B2)],
        ),
      ),
      child: Scaffold(
        backgroundColor: Colors.transparent,
        drawer: _buildDrawer(),
        bottomNavigationBar: _buildBottomNavBar(),
        body: SafeArea(
          child: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildHeader(),

                // --- Greeting ---
                Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Text(
                    "Hello Farman Ullah 👋",
                    style: GoogleFonts.poppins(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                ),

                // --- Attendance & Grades Cards ---
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16.0),
                  child: Row(
                    children: [
                      Expanded(
                        child: _buildProgressCard(
                          title: "Attendance",
                          percent: overallAttendance / 100.0,
                          accent: Colors.blueAccent,
                          subtitle: "${overallAttendance.toStringAsFixed(1)}% overall",
                          icon: Icons.event_available,
                          onTap: () {},
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: _buildProgressCard(
                          title: "Grades",
                          percent: overallGrade / 100.0,
                          accent: Colors.orange,
                          subtitle: "$overallGrade% average",
                          icon: Icons.grade_rounded,
                          onTap: () {},
                        ),
                      ),
                    ],
                  ),
                ),

                const SizedBox(height: 20),

                // --- Analytics ---
                _buildSectionTitle("Analytics"),
                _buildAnalyticsChart(attendanceData, highlightDays: taskSubjects),

                const SizedBox(height: 20),

                // --- Categories ---
                _buildSectionTitle('Categories'),
                _buildCategoriesGrid(categories),
                const SizedBox(height: 20),
              ],
            ),
          ),
        ),
      ),
    );
  }

  // ---------------------------------------------------------------------------
  // Drawer
  // ---------------------------------------------------------------------------
  Widget _buildDrawer() {
    return Drawer(
      child: ListView(
        children: [
          DrawerHeader(
            decoration: const BoxDecoration(color: lurnivaBlue),
            child: Row(
              children: [
                ClipRRect(
                  borderRadius: BorderRadius.circular(40),
                  child: Image.asset(
                    'assets/images/Farman.jpg',
                    height: 60,
                    width: 60,
                    fit: BoxFit.fill,
                  ),
                ),
                const SizedBox(width: 16),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: const [
                    Text(
                      'Farman Ullah',
                      style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                    SizedBox(height: 4),
                    Text('Class 10-A', style: TextStyle(color: Colors.white70, fontSize: 14)),
                  ],
                ),
              ],
            ),
          ),
          ListTile(
            leading: const Icon(Icons.home),
            title: const Text('Home'),
            onTap: () => Navigator.pop(context),
          ),
          ListTile(
            leading: const Icon(Icons.chat),
            title: const Text('Chat Support'),
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => ChatSupportPage())),
          ),
          ListTile(
            leading: const Icon(Icons.feedback),
            title: const Text('Feedback'),
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => FeedbackPage())),
          ),
          const Divider(),
          ListTile(
            leading: const Icon(Icons.settings),
            title: const Text('Settings'),
            onTap: () => Navigator.push(
              context,
              MaterialPageRoute(
                builder: (_) => SettingsPage(
                  toggleTheme: widget.toggleTheme,
                  themeMode: widget.themeMode,
                  toggleNotifications: widget.toggleNotifications,
                  notificationsEnabled: widget.notificationsEnabled,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  // ---------------------------------------------------------------------------
  // Bottom NavBar
  // ---------------------------------------------------------------------------
  Widget _buildBottomNavBar() {
    return BottomNavigationBar(
      currentIndex: 0,
      selectedItemColor: lurnivaBlue,
      unselectedItemColor: Colors.grey,
      showSelectedLabels: false,
      showUnselectedLabels: false,
      type: BottomNavigationBarType.fixed,
      onTap: (index) {
        if (index == 3) {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (_) => ProfilePage(
                toggleTheme: widget.toggleTheme,
                themeMode: widget.themeMode,
                notificationsEnabled: widget.notificationsEnabled,
                toggleNotifications: widget.toggleNotifications,
              ),
            ),
          );
        } else if (index == 2) {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (_) => SettingsPage(
                toggleTheme: widget.toggleTheme,
                themeMode: widget.themeMode,
                toggleNotifications: widget.toggleNotifications,
                notificationsEnabled: widget.notificationsEnabled,
              ),
            ),
          );
        }
      },
      items: const [
        BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Home'),
        BottomNavigationBarItem(icon: Icon(Icons.search), label: 'Search'),
        BottomNavigationBarItem(icon: Icon(Icons.settings), label: 'Settings'),
        BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Profile'),
      ],
    );
  }

  // ---------------------------------------------------------------------------
  // Header
  // ---------------------------------------------------------------------------
  Widget _buildHeader() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
      decoration: const BoxDecoration(
        color: Colors.white,
        boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 6, offset: Offset(0, 2))],
        borderRadius: BorderRadius.only(bottomLeft: Radius.circular(20), bottomRight: Radius.circular(20)),
      ),
      child: Row(
        children: [
          Builder(
            builder: (context) => IconButton(
              icon: const Icon(Icons.menu, color: Color(0xFF0165FF)),
              onPressed: () => Scaffold.of(context).openDrawer(),
            ),
          ),
          const SizedBox(width: 8),
          ClipRRect(
            borderRadius: BorderRadius.circular(50),
            child: Image.asset('assets/images/logo_dark.png', height: 36, width: 36),
          ),
          const SizedBox(width: 10),
          ShaderMask(
            shaderCallback: (bounds) => const LinearGradient(
              colors: [Color(0xFF3C8CE7), Color(0xFF00EAFF)],
            ).createShader(Rect.fromLTWH(0, 0, bounds.width, bounds.height)),
            child: Text(
              'Lurniva',
              style: GoogleFonts.poppins(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.white),
            ),
          ),
          const Spacer(),
          IconButton(
            icon: const Icon(Icons.notifications, color: Color(0xFF0165FF)),
            onPressed: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => NotificationsPage()));
            },
          ),
        ],
      ),
    );
  }

  // ---------------------------------------------------------------------------
  // Section Title
  // ---------------------------------------------------------------------------
  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
      child: Align(
        alignment: Alignment.centerLeft,
        child: Text(
          title,
          style: GoogleFonts.poppins(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.white),
        ).animate().slideX(begin: -0.5, duration: 500.ms).fadeIn(duration: 500.ms),
      ),
    );
  }

  // ---------------------------------------------------------------------------
  // Progress Card
  // ---------------------------------------------------------------------------
  Widget _buildProgressCard({
  required String title,
  required double percent,
  required Color accent,
  required String subtitle,
  required IconData icon,
  required VoidCallback onTap,
}) {
  final pct = (percent.clamp(0.0, 1.0) * 100).toStringAsFixed(0);

  return Container(
    padding: const EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.white, // ✅ White background
      borderRadius: BorderRadius.circular(18),
      boxShadow: const [
        BoxShadow(color: Colors.black12, blurRadius: 8, offset: Offset(0, 4)),
      ],
    ),
    child: Column(
      children: [
        Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: accent.withOpacity(.15),
                shape: BoxShape.circle,
              ),
              child: Icon(icon, color: accent, size: 18),
            ),
            const SizedBox(width: 8),
            Expanded(
              child: Text(
                title,
                style: const TextStyle(
                  fontWeight: FontWeight.w700,
                  color: Colors.black, // ✅ Black title text
                ),
                overflow: TextOverflow.ellipsis,
              ),
            ),
            const SizedBox(width: 8),
            Text(
              "$pct%",
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: accent, // keep accent color for percentage
              ),
            ),
          ],
        ),
        const SizedBox(height: 14),
        Stack(
          alignment: Alignment.center,
          children: [
            SizedBox(
              height: 76,
              width: 76,
              child: CircularProgressIndicator(
                value: percent.clamp(0.0, 1.0),
                strokeWidth: 8,
                backgroundColor: Colors.grey[200],
                valueColor: AlwaysStoppedAnimation(accent),
              ),
            ),
            Text(
              "$pct%",
              style: const TextStyle(color: Colors.black), // ✅ Black inside circle
            ),
          ],
        ),
        const SizedBox(height: 10),
        Text(
          subtitle,
          style: const TextStyle(fontSize: 12, color: Colors.black), // ✅ Black subtitle
          textAlign: TextAlign.center,
        ),
        const SizedBox(height: 10),
        SizedBox(
          width: double.infinity,
          child: ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: accent, // keep accent button color
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(10),
              ),
            ),
            onPressed: onTap,
            child: const Text("View details"),
          ),
        ),
      ],
    ),
  );
}

  // ---------------------------------------------------------------------------
// Upgraded Analytics Chart with Animations & Modern UI
// ---------------------------------------------------------------------------
Widget _buildAnalyticsChart(
  Map<String, double> data, {
  Set<String> highlightDays = const {},
}) {
  final entries = data.entries.toList();
  if (entries.isEmpty) {
    return _analyticsShell(
      child: const Center(
        child: Text(
          "No analytics data",
          style: TextStyle(color: Colors.black54, fontSize: 14),
        ),
      ),
    );
  }

  return _analyticsShell(
    child: SizedBox(
      height: 280,
      child: LayoutBuilder(
        builder: (context, constraints) {
          const double maxBarHeight = 160;
          const double minBarWidth = 28;
          const double barGap = 20;

          final barCount = entries.length;

          return SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 6),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: List.generate(barCount, (i) {
                  final day = entries[i].key;
                  final value = entries[i].value.clamp(0, 100);
                  final barHeight = (value / 100.0) * maxBarHeight;

                  final isHighlighted = highlightDays.contains(day);

                  return Padding(
                    padding: EdgeInsets.only(right: i == barCount - 1 ? 0 : barGap),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.end,
                      children: [
                        // % label
                        Text(
                          "${value.toStringAsFixed(0)}%",
                          style: const TextStyle(
                            fontSize: 12,
                            fontWeight: FontWeight.w600,
                            color: Colors.black87,
                          ),
                        ),
                        const SizedBox(height: 6),

                        // Animated Bar
                        TweenAnimationBuilder<double>(
                          duration: const Duration(milliseconds: 800),
                          curve: Curves.easeOutCubic,
                          tween: Tween(begin: 0, end: barHeight),
                          builder: (context, animatedHeight, child) {
                            return GestureDetector(
                              onTap: () {
                                ScaffoldMessenger.of(context).showSnackBar(
                                  SnackBar(
                                    content: Text(
                                        "📊 $day → ${value.toStringAsFixed(1)}%"),
                                    duration: const Duration(seconds: 2),
                                  ),
                                );
                              },
                              child: Container(
                                width: minBarWidth,
                                height: animatedHeight,
                                decoration: BoxDecoration(
                                  gradient: LinearGradient(
                                    colors: [Colors.blueAccent, Colors.lightBlueAccent],
                                    begin: Alignment.bottomCenter,
                                    end: Alignment.topCenter,
                                  ),
                                  borderRadius: BorderRadius.circular(10),
                                  boxShadow: const [
                                    BoxShadow(
                                      color: Colors.black26,
                                      blurRadius: 6,
                                      offset: Offset(0, 4),
                                    ),
                                  ],
                                ),
                              ),
                            );
                          },
                        ),

                        const SizedBox(height: 10),

                        // Day Label
                        SizedBox(
                          width: minBarWidth + 10,
                          child: Text(
                            day,
                            textAlign: TextAlign.center,
                            style: const TextStyle(
                              fontSize: 12,
                              fontWeight: FontWeight.w500,
                              color: Colors.black87,
                            ),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ),

                        // Highlight Marker
                        if (isHighlighted) ...[
                          const SizedBox(height: 6),
                          GestureDetector(
                            onTap: () {
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (_) =>
                                      TaskDetailsPage(subject: day),
                                ),
                              );
                            },
                            child: Container(
                              width: 14,
                              height: 14,
                              decoration: BoxDecoration(
                                color: Colors.orange,
                                shape: BoxShape.circle,
                                boxShadow: [
                                  BoxShadow(
                                    color: Colors.orange.withOpacity(0.6),
                                    blurRadius: 6,
                                    spreadRadius: 2,
                                  ),
                                ],
                              ),
                            ),
                          ),
                        ]
                      ],
                    ),
                  );
                }),
              ),
            ),
          );
        },
      ),
    ),
  );
}

// Wrapper Card UI
Widget _analyticsShell({required Widget child}) {
  return Container(
    margin: const EdgeInsets.symmetric(horizontal: 16),
    padding: const EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.white,
      borderRadius: BorderRadius.circular(20),
      boxShadow: const [
        BoxShadow(color: Colors.black12, blurRadius: 8, offset: Offset(0, 4)),
      ],
    ),
    child: child,
  );
}

  // ---------------------------------------------------------------------------
  // Categories Grid
  // ---------------------------------------------------------------------------
  Widget _buildCategoriesGrid(List<Map<String, dynamic>> filteredCategories) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: GridView.builder(
        shrinkWrap: true,
        physics: const NeverScrollableScrollPhysics(),
        itemCount: filteredCategories.length,
        gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
          crossAxisCount: 3,
          mainAxisSpacing: 16,
          crossAxisSpacing: 16,
          childAspectRatio: 0.9,
        ),
        itemBuilder: (context, index) {
          final item = filteredCategories[index];
          return GestureDetector(
            onTap: () {
              switch (item['title']) {
                case 'Subjects':
                  Navigator.push(context, MaterialPageRoute(builder: (_) => const SubjectsPage()));
                  break;
                case 'Calendar':
                  Navigator.push(context, MaterialPageRoute(builder: (_) => CalendarPage()));
                  break;
                case 'Leave':
                  Navigator.push(context, MaterialPageRoute(builder: (_) => const LeavePage()));
                  break;
                default:
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text('${item['title']} feature coming soon!')),
                  );
              }
            },
            child: Container(
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(18),
                boxShadow: [
                  BoxShadow(
                    color: Colors.grey.withOpacity(0.25),
                    blurRadius: 12,
                    spreadRadius: 1,
                    offset: const Offset(0, 6),
                  ),
                ],
              ),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Container(
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(color: item['color'], shape: BoxShape.circle),
                    child: Icon(item['icon'], size: 26, color: Colors.white),
                  ),
                  const SizedBox(height: 10),
                  Text(item['title'], style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w500)),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}


