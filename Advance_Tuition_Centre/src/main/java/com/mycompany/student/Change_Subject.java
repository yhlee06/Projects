package com.mycompany.student;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.time.LocalDate;
import java.time.LocalTime;
import java.time.format.DateTimeFormatter;

import javax.swing.JFrame;
import com.mycompany.edit.Add;
import com.mycompany.edit.Read;
import com.mycompany.edit.Update;
import com.mycompany.gui.*;

public class Change_Subject {
	static Base_Frame base;
	static Dropdown_Frame current_subject;
	static Dropdown_Frame new_subject;
	static Read file = new Read();

	public static void Change_Subject(Student user) {
		String[] class_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt");
		String[] class_list = new String[class_data.length];
		String[] subject;
		for (int i = 0; i < class_data.length; i++) {
			subject = class_data[i].split(";");
			class_list[i] = String.format("%s", subject[2]);
		}

		base = new Base_Frame("Change Subject", 500, 450);

		Label_Frame title_label = new Label_Frame("Title", 33, 10, 83, 26);
		title_label.font(16);
		Label_Frame current_subject_label = new Label_Frame("Current Subject", 33, 50, 130, 26);
		current_subject_label.font(16);
		Label_Frame new_subject_label = new Label_Frame("New Subject", 33, 90, 100, 26);
		new_subject_label.font(16);
		Label_Frame title_content_label = new Label_Frame("Subject Transfer Request", 165, 10, 200, 24);
		new_subject_label.font(17);

		current_subject = new Dropdown_Frame(280, 24, 165, 50, class_list);
		new_subject = new Dropdown_Frame(280, 24, 165, 90, class_list);

		Button_Frame confirm_button = new Button_Frame("CONFIRM", 100, 34, 100, 300, e -> update(user));
		Button_Frame cancel_button = new Button_Frame("CANCEL", 100, 34, 270, 300, e -> {
			base.dispose();
			Student_Menu.menu();
		});

		base.add_widget(title_label);
		base.add_widget(current_subject_label);
		base.add_widget(new_subject_label);
		base.add_widget(title_content_label);
		base.add_widget(current_subject);
		base.add_widget(new_subject);
		base.add_widget(confirm_button);
		base.add_widget(cancel_button);
		base.setVisible(true);
		base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
		base.addWindowListener(new WindowAdapter() {
			public void windowClosing(WindowEvent e) {
				Request_Menu.menu();
				base.dispose();
			}
		});

	}

	private static void update(Student user) {
		Add add_data = new Add();
		Update update_total = new Update();
		String[] total_list = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt");

		String[] current;
		int request_total = 0;
		for (int i = 0; i < total_list.length; i++) {
			current = total_list[i].split(";");
			if (current[0].equals("request")) {
				request_total = Integer.parseInt(current[1]) + 1;
			}
		}

		String request_id = String.format("RQ%03d", request_total);

		String currentS = current_subject.selection();
		String newS = new_subject.selection();
		String message = "I would like to change my subject from " + currentS + " to " + newS;

		String student_id = user.get("id");
		LocalDate currentDate = LocalDate.now();
		LocalTime currentTime = LocalTime.now();
		DateTimeFormatter dateFormatter = DateTimeFormatter.ofPattern("dd-MM-yyyy");
		DateTimeFormatter timeFormatter = DateTimeFormatter.ofPattern("HHmm");
		String formattedDate = currentDate.format(dateFormatter);
		String formattedTime = currentTime.format(timeFormatter);
		
		String new_request_data = String.format("%s;%s;%s;%s;%s;pending", request_id, student_id, message, formattedDate, formattedTime);

		add_data.add_to_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/request.txt", new_request_data);

		update_total.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt", "request", 1,
				String.valueOf(request_total));

		Message_Frame.message_frame("Success", "Subject change request submitted!");

		base.dispose();
		Request_Menu.menu();
	}
}
