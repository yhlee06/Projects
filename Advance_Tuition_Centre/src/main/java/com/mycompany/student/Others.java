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

public class Others {
	static Base_Frame base;
	static Input_Frame title;
	static TextArea_Frame message;
	static Read file = new Read();
	
	public static void Others(Student user) {
		base = new Base_Frame("Others", 520, 450);

		Label_Frame title_label = new Label_Frame("Title", 33, 10, 83, 26);
		title_label.font(16);
		Label_Frame message_label = new Label_Frame("Message", 33, 50, 70, 26);
		message_label.font(16);
		
		title = new Input_Frame(280, 24, 140, 10);
		title.set_text("");
		message = new TextArea_Frame(280, 250, 140, 50);
		message.set_text("");
		
		Button_Frame confirm_button = new Button_Frame("CONFIRM", 100, 34, 100, 350, e -> update(user));
		Button_Frame cancel_button = new Button_Frame("CANCEL", 100, 34, 270, 350, e -> {
			base.dispose();
			Student_Menu.menu();
		});

		base.add_widget(title_label);
		base.add_widget(message_label);
		base.add_widget(title);
		base.add_widget(message);
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
		try {
			String messageInput = message.get_input();
			
			if(messageInput.isEmpty()) {
				throw new Exception("Message must not be empty.");
			}
			
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

			String student_id = user.get("id");
			LocalDate currentDate = LocalDate.now();
			LocalTime currentTime = LocalTime.now();
			DateTimeFormatter dateFormatter = DateTimeFormatter.ofPattern("dd-MM-yyyy");
			DateTimeFormatter timeFormatter = DateTimeFormatter.ofPattern("HHmm");
			String formattedDate = currentDate.format(dateFormatter);
			String formattedTime = currentTime.format(timeFormatter);
			
			String new_request_data = String.format("%s;%s;%s;%s;%s;pending", request_id, student_id, message.get_input() , formattedDate, formattedTime);

			add_data.add_to_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/request.txt", new_request_data);

			update_total.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt", "request", 1, 
					String.valueOf(request_total));

			Message_Frame.message_frame("Success", "Subject change request submitted!");

			base.dispose();
			Request_Menu.menu();
		} catch (Exception e) {
			Message_Frame.message_frame("Error", e.getMessage());
		}
	}
}
