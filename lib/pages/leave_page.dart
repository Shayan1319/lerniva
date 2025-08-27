import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:file_picker/file_picker.dart';

class LeavePage extends StatefulWidget {
  const LeavePage({super.key});

  @override
  State<LeavePage> createState() => _LeavePageState();
}

class _LeavePageState extends State<LeavePage> {
  final _formKey = GlobalKey<FormState>();
  String? _studentName;
  String? _rollNumber;
  String? _classSection;
  String? _applicationType;
  String? _reason;
  DateTimeRange? _leaveDates;
  String? _documentPath;

  final List<String> _applicationTypes = [
    'Leave',
    'Fee Concession',
    'Other Application',
  ];

  // 📅 Pick Leave Dates
  void _pickDates() async {
    final picked = await showDateRangePicker(
      context: context,
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 365)),
    );
    if (picked != null) {
      setState(() => _leaveDates = picked);
    }
  }

  // 📂 Pick Document from System
  void _pickDocument() async {
    FilePickerResult? result = await FilePicker.platform.pickFiles(
      type: FileType.custom,
      allowedExtensions: ['pdf', 'jpg', 'png'],
    );

    if (result != null) {
      setState(() {
        _documentPath = result.files.single.name;
      });
    }
  }

  // ✅ Submit Application
  void _submit() {
    if (_formKey.currentState!.validate()) {
      if (_leaveDates == null) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Please select leave dates")),
        );
        return;
      }

      _formKey.currentState!.save();

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Application Submitted Successfully")),
      );

      Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    String formattedDates = "";
    if (_leaveDates != null) {
      final format = DateFormat('dd/MM/yyyy');
      formattedDates =
          "${format.format(_leaveDates!.start)} → ${format.format(_leaveDates!.end)}";
    }

    return Scaffold(
      backgroundColor: Colors.grey.shade100, // ✅ Light background
      appBar: AppBar(
        elevation: 0,
        backgroundColor: const Color(0xFF3B38FF), // ✅ Blue navbar
        title: const Text("Leave / Application"),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20.0),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              // 🧑 Student Info Card
              Card(
                elevation: 4,
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16)),
                child: Padding(
                  padding: const EdgeInsets.all(20.0),
                  child: Column(
                    children: [
                      TextFormField(
                        decoration: const InputDecoration(
                          labelText: "Student Name",
                          border: OutlineInputBorder(),
                        ),
                        onSaved: (val) => _studentName = val,
                        validator: (val) =>
                            val == null || val.isEmpty ? "Enter name" : null,
                      ),
                      const SizedBox(height: 15),
                      TextFormField(
                        decoration: const InputDecoration(
                          labelText: "Roll Number",
                          border: OutlineInputBorder(),
                        ),
                        onSaved: (val) => _rollNumber = val,
                        validator: (val) =>
                            val == null || val.isEmpty ? "Enter roll number" : null,
                      ),
                      const SizedBox(height: 15),
                      TextFormField(
                        decoration: const InputDecoration(
                          labelText: "Class / Section",
                          border: OutlineInputBorder(),
                        ),
                        onSaved: (val) => _classSection = val,
                        validator: (val) =>
                            val == null || val.isEmpty ? "Enter class/section" : null,
                      ),
                    ],
                  ),
                ),
              ),

              const SizedBox(height: 20),

              // 📝 Application Form
              Card(
                elevation: 4,
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16)),
                child: Padding(
                  padding: const EdgeInsets.all(20.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      DropdownButtonFormField<String>(
                        decoration: const InputDecoration(
                          labelText: "Application Type",
                          border: OutlineInputBorder(),
                        ),
                        value: _applicationType,
                        items: _applicationTypes
                            .map((type) => DropdownMenuItem(
                                value: type, child: Text(type)))
                            .toList(),
                        onChanged: (val) =>
                            setState(() => _applicationType = val),
                        validator: (val) =>
                            val == null ? "Please select type" : null,
                      ),
                      const SizedBox(height: 15),
                      TextFormField(
                        decoration: const InputDecoration(
                          labelText: "Reason",
                          border: OutlineInputBorder(),
                        ),
                        maxLines: 3,
                        onSaved: (val) => _reason = val,
                        validator: (val) =>
                            val == null || val.isEmpty ? "Enter reason" : null,
                      ),
                      const SizedBox(height: 15),

                      // 📅 Date Picker
                      GestureDetector(
                        onTap: _pickDates,
                        child: Container(
                          padding: const EdgeInsets.all(16),
                          decoration: BoxDecoration(
                            border: Border.all(color: Colors.grey.shade400),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                formattedDates.isEmpty
                                    ? "Select Leave Dates"
                                    : formattedDates,
                                style: TextStyle(
                                  fontSize: 16,
                                  color: formattedDates.isEmpty
                                      ? Colors.grey
                                      : Colors.black,
                                ),
                              ),
                              const Icon(Icons.calendar_today,
                                  color: Color(0xFF3B38FF)), // ✅ Blue icon
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 15),

                      // 📂 Upload File
                      ElevatedButton.icon(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF3B38FF), // ✅ Blue
                          foregroundColor: Colors.white, // White text
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                        ),
                        icon: const Icon(Icons.upload_file),
                        label: const Text("Upload Document"),
                        onPressed: _pickDocument,
                      ),

                      if (_documentPath != null) ...[
                        const SizedBox(height: 10),
                        Card(
                          color: Colors.grey.shade200,
                          child: ListTile(
                            leading: const Icon(Icons.insert_drive_file,
                                color: Color(0xFF3B38FF)),
                            title: Text(
                              _documentPath!,
                              style: const TextStyle(fontSize: 14),
                            ),
                          ),
                        ),
                      ],
                    ],
                  ),
                ),
              ),

              const SizedBox(height: 30),

              // ✅ Submit Button
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF3B38FF), // ✅ Blue button
                    foregroundColor: Colors.white, // White text
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                    padding: const EdgeInsets.symmetric(vertical: 16),
                  ),
                  onPressed: _submit,
                  child: const Text(
                    "Submit Application",
                    style: TextStyle(fontSize: 16),
                  ),
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}
