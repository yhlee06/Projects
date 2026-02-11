package com.mycompany.student;

class StudentClass {
	String studentId;
	String classId;

	public StudentClass(String student_id, String class_id) {
		this.studentId = student_id;
		this.classId = class_id;
	}
}

class ClassInfo {
	String classId;
	String subject;

	public ClassInfo(String class_id, String subject) {
		this.classId = class_id;
		this.subject = subject;
	}
}

class ClassSchedule {
	String classId;
	String date;
	String startTime;
	String endTime;
	String location;

	public ClassSchedule(String class_id, String date, String startTime, String endTime, String location) {
		this.classId = class_id;
		this.date = date;
		this.startTime = startTime;
		this.endTime = endTime;
		this.location = location;
	}
}

class CombineSchedule {
	String studentId;
	String classId;
	String subject;
	String date;
	String start_time;
	String end_time;
	String location;

	public CombineSchedule(String student_id, Object class_id, String subject, String date, String start_time, String end_time, String location) {
		this.studentId = student_id;
		this.classId = (String) class_id;
		this.subject = subject;
		this.date = date;
		this.start_time = start_time;
		this.end_time = end_time;
		this.location = location;
	}
	
	public String get(String info) {
		switch (info) {
		case "id":
			return studentId;
		case "subject":
			return subject;
		case "class_id":
			return classId;
		case "date":
			return date;
		case "start_time":
			return start_time;
		case "end_time":
			return end_time;
		case "location":
			return location;
		default:
			return "";
		}

	}
}
