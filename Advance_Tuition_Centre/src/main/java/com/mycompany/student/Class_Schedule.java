package com.mycompany.student;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.util.ArrayList;
import java.util.List;

import javax.swing.JFrame;

import com.mycompany.edit.Read;
import com.mycompany.gui.*;

public class Class_Schedule {
	static Base_Frame base;
	static Read file = new Read();

	public static void Class_Schedule(Student user) {
		
		List<StudentClass> studentClassList = new ArrayList<>();
		List<ClassInfo> classInfoList = new ArrayList<>();
		List<ClassSchedule> classScheduleList = new ArrayList<>();
		List<CombineSchedule> combineScheduleList = new ArrayList<>();
		List<String[]> filteredDataList = new ArrayList<>();
		
		String[] student = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt");;
		for (String line : student) {
		    String[] parts = line.split(";");
		    String studentId = parts[1];
		    String classId = parts[2];  
		    studentClassList.add(new StudentClass(studentId, classId));
		}
		
		String[] subject = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt");;
		for (String line : subject) {
			 String[] parts = line.split(";");
			 String classId = parts[0];   
			 String subjectName = parts[2];
			 classInfoList.add(new ClassInfo(classId, subjectName));  
		}
		
		String[] timetable = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_schedule.txt");;
		for (String line : timetable) {
			 String[] parts = line.split(";");
			 String classId = parts[1]; 
			 String date = parts[2];
			 String startTime = parts[3];
			 String endTime = parts[4];
			 String location = parts[5];
			 classScheduleList.add(new ClassSchedule(classId, date, startTime, endTime, location));  
		}
		
		for (StudentClass sc : studentClassList) {
			 for (ClassInfo ci : classInfoList) {  
				 for (ClassSchedule sch : classScheduleList) {
					 if (sc.classId.equals(ci.classId) && ci.classId.equals(sch.classId)) {          
						 combineScheduleList.add(new CombineSchedule(sc.studentId, sc.classId, ci.subject, sch.date, sch.startTime, sch.endTime, sch.location));        
						 break;      
					 
					 }
				 }
			 }
		}
		
		for (int i = 0; i < combineScheduleList.size(); i++) {
		    CombineSchedule entry = combineScheduleList.get(i);

			if (entry.studentId.equals(user.get("id"))) {
				filteredDataList.add(new String[]{
		            (String) entry.studentId, 
		            entry.classId, 
		            entry.subject,
		            entry.date,
		            entry.start_time,
		            entry.end_time,
		            entry.location
		            
		        });
		    }
		}
		       
		base = new Base_Frame("Class Schedule", 1000, 700);
		
        Label_Frame title = new Label_Frame("Class Schedule", 30, 10, 280, 35);
        title.font(true, 25);
        
		int x = 40, y = 50;
		int width = 420, height = 160;
		int padding = 20;
		int count = 0;
		
        base.add_widget(title);
        

		for (CombineSchedule cs : combineScheduleList) {
			if (!cs.studentId.equals(user.get("id"))) {
				continue;
			}

			Panel_Frame panel = new Panel_Frame("Class: " + cs.classId, x, y, width, height);

			Label_Frame subject1 = new Label_Frame("Subject: " + cs.subject, 20, 30, 300, 25);
			Label_Frame time = new Label_Frame("Time: " + cs.start_time + " - " + cs.end_time, 20, 60, 300, 25);
			Label_Frame date = new Label_Frame("Date: " + cs.date, 20, 90, 300, 25);
			Label_Frame location = new Label_Frame("Location: " + cs.location, 20, 120, 300, 25);

			panel.add_weight(subject1);
			panel.add_weight(time);
			panel.add_weight(date);
			panel.add_weight(location);

			base.add_widget(panel);

			count++;
			if (count % 2 == 0) {
				x = 40;
				y += height + padding;
			} else {
				x += width + padding;
			}
		}

		base.setVisible(true);
		base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
		base.addWindowListener(new WindowAdapter() {
			public void windowClosing(WindowEvent e) {
				Student_Menu.menu();
				base.dispose();
			}
		});
	}

}
