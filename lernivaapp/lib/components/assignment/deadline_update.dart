import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

class DeadlineUpdatePage extends StatefulWidget {
  const DeadlineUpdatePage({super.key});

  @override
  State<DeadlineUpdatePage> createState() => _DeadlineUpdatePageState();
}

class _DeadlineUpdatePageState extends State<DeadlineUpdatePage> {
  // Dummy list of tasks (in real app, fetch from backend)
  List<Map<String, dynamic>> tasks = [
    {
      "title": "Math Assignment",
      "desc": "Complete exercises 1-20",
      "deadline": DateTime.now().add(const Duration(days: 5)),
    },
    {
      "title": "Science Project",
      "desc": "Prepare volcano model",
      "deadline": DateTime.now().add(const Duration(days: 7, hours: 4)),
    },
    {
      "title": "English Essay",
      "desc": "Write essay on climate change",
      "deadline": DateTime.now().add(const Duration(days: 3)),
    },
  ];

  // Pick new deadline
  Future<void> _updateDeadline(int index) async {
    DateTime? picked = await showDatePicker(
      context: context,
      initialDate: tasks[index]["deadline"] ?? DateTime.now(),
      firstDate: DateTime.now(),
      lastDate: DateTime(2100),
    );

    if (picked != null) {
      TimeOfDay? time = await showTimePicker(
        context: context,
        initialTime: TimeOfDay.fromDateTime(tasks[index]["deadline"]),
      );

      if (time != null) {
        DateTime newDeadline = DateTime(
            picked.year, picked.month, picked.day, time.hour, time.minute);
        setState(() {
          tasks[index]["deadline"] = newDeadline;
        });
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
                "✅ Deadline updated for '${tasks[index]["title"]}' to ${DateFormat('dd/MM/yyyy HH:mm').format(newDeadline)}"),
          ),
        );
      }
    }
  }

  // Calculate remaining time
  String getRemainingTime(DateTime deadline) {
    final now = DateTime.now();
    final difference = deadline.difference(now);

    if (difference.inDays >= 7) {
      return "${(difference.inDays / 7).ceil()} week(s) remaining";
    } else if (difference.inDays > 0) {
      return "${difference.inDays} day(s) remaining";
    } else if (difference.inHours > 0) {
      return "${difference.inHours} hour(s) remaining";
    } else if (difference.inMinutes > 0) {
      return "${difference.inMinutes} minute(s) remaining";
    } else {
      return "Deadline passed!";
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Update Task Deadlines"),
        backgroundColor: Colors.deepPurple,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              "Tasks & Deadlines",
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            Expanded(
              child: tasks.isEmpty
                  ? const Center(child: Text("No tasks available"))
                  : ListView.builder(
                      itemCount: tasks.length,
                      itemBuilder: (context, index) {
                        final task = tasks[index];
                        final deadline = task["deadline"] as DateTime?;
                        return Card(
                          elevation: 3,
                          margin: const EdgeInsets.symmetric(vertical: 6),
                          shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12)),
                          child: ListTile(
                            title: Text(task["title"],
                                style: const TextStyle(
                                    fontWeight: FontWeight.bold)),
                            subtitle: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(task["desc"]),
                                if (deadline != null)
                                  Text(
                                    "Deadline: ${DateFormat('dd/MM/yyyy HH:mm').format(deadline)}\n${getRemainingTime(deadline)}",
                                    style: const TextStyle(
                                        fontStyle: FontStyle.italic,
                                        color: Colors.redAccent),
                                  ),
                              ],
                            ),
                            isThreeLine: true,
                            trailing: IconButton(
                              icon: const Icon(Icons.edit_calendar,
                                  color: Colors.deepPurple),
                              onPressed: () => _updateDeadline(index),
                            ),
                          ),
                        );
                      },
                    ),
            ),
          ],
        ),
      ),
    );
  }
}
