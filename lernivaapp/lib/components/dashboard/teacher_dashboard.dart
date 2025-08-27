import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:flutter_animate/flutter_animate.dart';

// ===== Optional: hook up your real pages here =====
import '../assignment/assignment_lesson.dart';
import '../assignment/teacher_quiz_page.dart';
import '../assignment/deadline_update.dart';
import '../assignment/teacher_announcement_page.dart';

class TeacherDashboard extends StatefulWidget {
  final String teacherName;
  final String teacherDescription;
  final String courseName;
  final String classId;
  final String profileImageUrl;

  const TeacherDashboard({
    super.key,
    required this.teacherName,
    required this.teacherDescription,
    required this.courseName,
    required this.classId,
    required this.profileImageUrl,
  });

  @override
  State<TeacherDashboard> createState() => _TeacherDashboardState();
}

class _TeacherDashboardState extends State<TeacherDashboard>
    with SingleTickerProviderStateMixin {
  // Sidebar animation
  bool isSidebarOpen = false;
  late final AnimationController _sidebarController;
  late final Animation<double> _sidebarX;

  // Sliders
  final PageController _periodCtrl = PageController(viewportFraction: .9);
  final PageController _exploreCtrl = PageController(viewportFraction: .86);
  final PageController _updatesCtrl = PageController(viewportFraction: .9);

  int _periodIndex = 0;
  int _exploreIndex = 0;
  int _updatesIndex = 0;

  // Brand colors
  static const Color cBlue = Color(0xFF1DA1F2);
  static const Color cViolet = Color(0xFF794BC4);
  static const Color cTeal = Color(0xFF17C3B2);
  static const List<Color> brandGradient = [cBlue, cViolet, cTeal];

  @override
  void initState() {
    super.initState();
    _sidebarController =
        AnimationController(vsync: this, duration: const Duration(milliseconds: 280));
    _sidebarX = Tween<double>(begin: -270, end: 0).animate(
      CurvedAnimation(parent: _sidebarController, curve: Curves.easeInOut),
    );

    _periodCtrl.addListener(() {
      final i = (_periodCtrl.page ?? 0).round();
      if (i != _periodIndex) setState(() => _periodIndex = i);
    });
    _exploreCtrl.addListener(() {
      final i = (_exploreCtrl.page ?? 0).round();
      if (i != _exploreIndex) setState(() => _exploreIndex = i);
    });
    _updatesCtrl.addListener(() {
      final i = (_updatesCtrl.page ?? 0).round();
      if (i != _updatesIndex) setState(() => _updatesIndex = i);
    });
  }

  void _toggleSidebar() {
    setState(() {
      isSidebarOpen = !isSidebarOpen;
      if (isSidebarOpen) {
        _sidebarController.forward();
      } else {
        _sidebarController.reverse();
      }
    });
  }

  @override
  void dispose() {
    _sidebarController.dispose();
    _periodCtrl.dispose();
    _exploreCtrl.dispose();
    _updatesCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final bg = const LinearGradient(
      colors: brandGradient,
      begin: Alignment.topLeft,
      end: Alignment.bottomRight,
    );

    return GestureDetector(
      onTap: () {
        if (isSidebarOpen) _toggleSidebar();
      },
      child: Scaffold(
        body: Stack(
          children: [
            // Gradient background
            Container(decoration: BoxDecoration(gradient: bg)),

            SafeArea(
              child: Column(
                children: [
                  // Top Bar
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
                    decoration: const BoxDecoration(color: Colors.white, boxShadow: [
                      BoxShadow(
                        blurRadius: 10,
                        color: Colors.black12,
                        offset: Offset(0, 2),
                      )
                    ]),
                    child: Row(
                      children: [
                        IconButton(
                          onPressed: _toggleSidebar,
                          icon: const Icon(Icons.menu, color: cBlue),
                        ),
                        const Spacer(),
                        const Text(
                          'Dashboard',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.w700,
                            color: Colors.black,
                          ),
                        ),
                        const Spacer(),
                        IconButton(
                          onPressed: () {},
                          icon: const Icon(Icons.notifications_none, color: cBlue),
                        ),
                        CircleAvatar(
                          radius: 16,
                          backgroundImage: NetworkImage(widget.profileImageUrl),
                        ),
                      ],
                    ),
                  ),

                  // Body content
                  Expanded(
                    child: SingleChildScrollView(
                      padding: const EdgeInsets.fromLTRB(16, 16, 16, 24),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // Greeting card
                          _greetingCard().animate().fade().slideY(begin: .2),
                          const SizedBox(height: 16),

                          // Next Period
                          _sectionHeader('Next Period'),
                          SizedBox(
                            height: 120,
                            child: PageView(
                              controller: _periodCtrl,
                              children: _mockPeriods
                                  .map((p) => _PeriodCard(p))
                                  .toList(),
                            ),
                          ),
                          _dots(_periodIndex, _mockPeriods.length),

                          // Explore Academic
                          _sectionHeader('Explore Academic'),
                          SizedBox(
                            height: 140,
                            child: PageView(
                              controller: _exploreCtrl,
                              children: [_exploreGrid(context)],
                            ),
                          ),
                          _dots(_exploreIndex, 1), // since _exploreGrid is a single grid


                          // Recent Updates
                          _sectionHeader('Recent Updates'),
                          SizedBox(
                            height: 140,
                            child: PageView(
                              controller: _updatesCtrl,
                              children: _updates
                                  .map((u) => _UpdateCard(u))
                                  .toList(),
                            ),
                          ),
                          _dots(_updatesIndex, _updates.length),

                          // Info chips
                          Row(
                            children: [
                              Expanded(
                                child: _infoChip(
                                  title: 'Course',
                                  value: widget.courseName,
                                  icon: Icons.menu_book_outlined,
                                ),
                              ),
                              const SizedBox(width: 12),
                              Expanded(
                                child: _infoChip(
                                  title: 'Class ID',
                                  value: widget.classId,
                                  icon: Icons.tag,
                                ),
                              ),
                            ],
                          ).animate().fade().slideY(begin: .2),

                          // Analytics mini charts
                          _sectionHeader('Analytics'),
                          _miniChart('Attendance', Colors.white, cBlue),
                          _miniChart('Test Scores', Colors.white, cViolet),
                          _miniChart('Assignments', Colors.white, cTeal),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),

            // Sidebar
            AnimatedBuilder(
              animation: _sidebarController,
              builder: (context, child) {
                return Transform.translate(
                  offset: Offset(_sidebarX.value, 0),
                  child: child,
                );
              },
              child: SafeArea(
                child: Container(
                  width: 270,
                  height: double.infinity,
                  decoration: const BoxDecoration(
                    color: Colors.white,
                    boxShadow: [BoxShadow(blurRadius: 16, color: Colors.black26)],
                  ),
                  padding: const EdgeInsets.fromLTRB(18, 24, 18, 12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Menu',
                          style: TextStyle(
                              fontSize: 22, fontWeight: FontWeight.w800)),
                      const SizedBox(height: 16),
                      _sideItem(Icons.assignment_outlined, 'Assignments', () {
                        Navigator.push(context,
                            MaterialPageRoute(builder: (_) => AssignmentLessonPage()));
                      }),
                      _sideItem(Icons.quiz_outlined, 'Tasks / Quiz', () {
                        Navigator.push(context,
                            MaterialPageRoute(builder: (_) => TeacherTaskPage()));
                      }),
                      _sideItem(Icons.update, 'Deadlines', () {
                        Navigator.push(context,
                            MaterialPageRoute(builder: (_) => DeadlineUpdatePage()));
                      }),
                      _sideItem(Icons.campaign_outlined, 'Announcements', () {
                        Navigator.push(context,
                            MaterialPageRoute(builder: (_) => const TeacherAnnouncementPage()));
                      }),
                      _sideItem(Icons.check_circle_outline, 'Attendance', () {}),
                      _sideItem(Icons.video_library_outlined, 'Lectures', () {}),
                      _sideItem(Icons.schedule_outlined, 'Timetable', () {}),
                      _sideItem(Icons.people_alt_outlined, 'PTM / Meetings', () {}),
                      _sideItem(Icons.chat_bubble_outline, 'Parent Chat', () {}),
                      const Spacer(),
                      ElevatedButton.icon(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: cBlue,
                          minimumSize: const Size.fromHeight(44),
                          shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(14)),
                        ),
                        onPressed: _toggleSidebar,
                        icon: const Icon(Icons.close),
                        label: const Text('Close'),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _greetingCard() {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: const [BoxShadow(color: Colors.black12, blurRadius: 8)],
      ),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: cBlue.withOpacity(.1),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: const Text(
                    'Good Morning',
                    style: TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.w600,
                      color: cBlue,
                    ),
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  widget.teacherName.isEmpty ? 'Mr. Teacher' : widget.teacherName,
                  style: const TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.w800,
                    color: Colors.black,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  widget.teacherDescription,
                  style: const TextStyle(fontSize: 13, color: Colors.black54),
                ),
              ],
            ),
          ),
          CircleAvatar(
            radius: 28,
            backgroundImage: NetworkImage(widget.profileImageUrl),
          ),
        ],
      ),
    );
  }

  Widget _sectionHeader(String title) {
    return Padding(
      padding: const EdgeInsets.only(top: 20, bottom: 10),
      child: Row(
        children: [
          Text(
            title,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 18,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(width: 8),
          Container(
            width: 30,
            height: 4,
            decoration: BoxDecoration(
              gradient: const LinearGradient(colors: brandGradient),
              borderRadius: BorderRadius.circular(10),
            ),
          ),
        ],
      ),
    );
  }

  Widget _dots(int index, int total) {
    return Padding(
      padding: const EdgeInsets.only(top: 6, bottom: 10),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: List.generate(
          total,
          (i) => AnimatedContainer(
            duration: 250.ms,
            margin: const EdgeInsets.symmetric(horizontal: 3),
            width: i == index ? 20 : 8,
            height: 8,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(16),
              gradient: i == index
                  ? const LinearGradient(colors: brandGradient)
                  : null,
              color: i == index ? null : Colors.white.withOpacity(.6),
            ),
          ),
        ),
      ),
    );
  }

  Widget _infoChip({required String title, required String value, required IconData icon}) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: const [BoxShadow(blurRadius: 8, color: Colors.black12)],
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              gradient: const LinearGradient(colors: brandGradient),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(icon, color: Colors.white, size: 20),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title,
                    style: const TextStyle(
                        fontSize: 12, color: Colors.black54, fontWeight: FontWeight.w600)),
                const SizedBox(height: 4),
                Text(value, style: const TextStyle(fontWeight: FontWeight.w700, color: Colors.black)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _miniChart(String title, Color bg, Color barColor) {
    return Card(
      margin: const EdgeInsets.symmetric(vertical: 8),
      color: bg,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
          Text(title, style: const TextStyle(fontWeight: FontWeight.w700, color: Colors.black)),
          const SizedBox(height: 12),
          SizedBox(
            height: 140,
            child: BarChart(
              BarChartData(
                alignment: BarChartAlignment.spaceAround,
                barGroups: [
                  BarChartGroupData(x: 0, barRods: [BarChartRodData(toY: 7, color: barColor)]),
                  BarChartGroupData(x: 1, barRods: [BarChartRodData(toY: 5, color: barColor)]),
                  BarChartGroupData(x: 2, barRods: [BarChartRodData(toY: 6, color: barColor)]),
                  BarChartGroupData(x: 3, barRods: [BarChartRodData(toY: 8, color: barColor)]),
                ],
                borderData: FlBorderData(show: false),
                titlesData: FlTitlesData(show: false),
                gridData: FlGridData(show: false),
              ),
            ),
          ),
        ]),
      ),
    ).animate().fade().slideY(begin: .15);
  }

  Widget _sideItem(IconData icon, String title, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(12),
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 10),
        child: Row(
          children: [
            Icon(icon, color: cBlue),
            const SizedBox(width: 12),
            Text(title, style: const TextStyle(fontSize: 16, color: Colors.black)),
          ],
        ),
      ),
    );
  }

  // ==== Explore Academic Pages ====
Widget _exploreGrid(BuildContext context) {
  final items = [
    {
      'icon': Icons.assignment_outlined,
      'label': 'Assignments',
      'onTap': () => Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => AssignmentLessonPage()),
          )
    },
    {
      'icon': Icons.quiz_outlined,
      'label': 'Tasks',
      'onTap': () => Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => TeacherTaskPage()),
          )
    },
    {
      'icon': Icons.update,
      'label': 'Deadlines',
      'onTap': () => Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => DeadlineUpdatePage()),
          )
    },
    {
      'icon': Icons.campaign_outlined,
      'label': 'Announcements',
      'onTap': () => Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => const TeacherAnnouncementPage()),
          )
    },
    {
      'icon': Icons.menu_book_outlined,
      'label': 'Class Diary',
      'onTap': () {
        // TODO: Add navigation for Class Diary
      }
    },
    {
      'icon': Icons.people_outline,
      'label': 'Students',
      'onTap': () {
        // TODO: Add navigation for Students page
      }
    },
  ];

  return GridView.builder(
    shrinkWrap: true,
    physics: const NeverScrollableScrollPhysics(),
    itemCount: items.length,
    gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
      crossAxisCount: 3, // ✅ three icons in a row
      mainAxisSpacing: 12,
      crossAxisSpacing: 12,
      childAspectRatio: 0.9,
    ),
    itemBuilder: (context, index) {
      final item = items[index];
      return _exploreCard(
        item['icon'] as IconData,
        item['label'] as String,
        item['onTap'] as VoidCallback,
      );
    },
  );
}

Widget _exploreCard(IconData icon, String title, VoidCallback onTap) {
  return InkWell(
    onTap: onTap,
    borderRadius: BorderRadius.circular(16),
    child: Container(
      decoration: BoxDecoration(
        color: Colors.white, // ✅ pure white background
        borderRadius: BorderRadius.circular(16),
        boxShadow: const [BoxShadow(blurRadius: 6, color: Colors.black12)],
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(icon, color: Colors.black87, size: 32), // ✅ plain black icon
          const SizedBox(height: 8),
          Text(
            title,
            textAlign: TextAlign.center,
            style: const TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: Colors.black,
            ),
            maxLines: 2,
          ),
        ],
      ),
    ),
  );
}


  // ==== Recent Updates ====
  final List<Map<String, String>> _updates = [
    {'title': 'New Assignment Posted', 'desc': 'Due next week'},
    {'title': 'Quiz Results Published', 'desc': 'Check scores now'},
    {'title': 'Deadline Extended', 'desc': 'Project submission +2 days'},
  ];

  Widget _UpdateCard(Map<String, String> u) {
    return Container(
      margin: const EdgeInsets.only(right: 12),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: const [BoxShadow(color: Colors.black12, blurRadius: 8)],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(u['title'] ?? '',
              style: const TextStyle(
                  fontWeight: FontWeight.w700, color: Colors.black)),
          const SizedBox(height: 6),
          Text(u['desc'] ?? '',
              style: const TextStyle(fontSize: 13, color: Colors.black54)),
        ],
      ),
    );
  }

  // ==== Mock Period Data ====
  final List<Map<String, String>> _mockPeriods = [
    {'subject': 'Mathematics', 'time': '9:00 AM'},
    {'subject': 'Physics', 'time': '11:00 AM'},
    {'subject': 'Chemistry', 'time': '2:00 PM'},
  ];

  Widget _PeriodCard(Map<String, String> p) {
    return Container(
      margin: const EdgeInsets.only(right: 12),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: const [BoxShadow(color: Colors.black12, blurRadius: 8)],
      ),
      child: Row(
        children: [
          Container(
            width: 46,
            height: 46,
            decoration: BoxDecoration(
              gradient: const LinearGradient(colors: brandGradient),
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Icon(Icons.access_time, color: Colors.white),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(p['subject'] ?? '',
                    style: const TextStyle(
                        fontWeight: FontWeight.w700, color: Colors.black)),
                const SizedBox(height: 4),
                Text(p['time'] ?? '',
                    style:
                        const TextStyle(fontSize: 13, color: Colors.black54)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

