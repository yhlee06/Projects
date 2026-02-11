
package com.mycompany.tutor;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.time.LocalDate;
import java.time.LocalTime;
import java.time.format.DateTimeFormatter;
import java.time.format.DateTimeParseException;

import javax.swing.JFrame;

import com.mycompany.edit.Add;
import com.mycompany.edit.Read;
import com.mycompany.edit.Update;
import com.mycompany.gui.Base_Frame;
import com.mycompany.gui.Button_Frame;
import com.mycompany.gui.Input_Frame;
import com.mycompany.gui.Label_Frame;
import com.mycompany.gui.Message_Frame;

public class Add_Schedule {
    static Base_Frame add_schedule_table;
    static Input_Frame class_id_input_field;
    static Input_Frame date;
    static Input_Frame start_time;
    static Input_Frame end_time;
    static Input_Frame venue;
    static Add add_feature = new Add();
    static Read file = new Read();
    static Update update_feature = new Update();
        
    public static void Add_Class(Tutor user){

        /* Base Frame and Label */
        add_schedule_table = new Base_Frame("Add Schedule Table", 568, 464);
        Label_Frame title = new Label_Frame("Enter new class schedule ",22,10,208,39);
        title.font(true,16);
        
        /* Labels */
        Label_Frame class_id_label = new Label_Frame("Class ID", 22, 60, 83, 26);
        class_id_label.font(14);
        class_id_input_field = new Input_Frame(180, 26, 180, 60);
        Label_Frame date_label = new Label_Frame("Date (DD-MM-YYYY)", 22, 100, 150, 26);
        date_label.font(14);
        Label_Frame start_time_label = new Label_Frame("Start Time (HHMM)", 22, 140, 150, 26); // Updated label
        start_time_label.font(14);
        Label_Frame end_time_label = new Label_Frame("End Time (HHMM)", 22, 180, 150, 26); // Updated label
        end_time_label.font(14); 
        Label_Frame venue_label = new Label_Frame("Venue", 22, 220, 79, 26);
        venue_label.font(14);

        /* Input Frames */
        date = new Input_Frame(180, 26, 180, 100);
        start_time = new Input_Frame(180, 26, 180, 140);
        end_time = new Input_Frame(180, 26, 180, 180);
        venue = new Input_Frame(180, 26, 180, 220);
        
        /* Add Button */
        Button_Frame add_button = new Button_Frame("Add",150,34,380,380,e -> add(user));
        
        add_schedule_table.add_widget(title);
        add_schedule_table.add_widget(class_id_label);
        add_schedule_table.add_widget(class_id_input_field);
        add_schedule_table.add_widget(date_label);
        add_schedule_table.add_widget(start_time_label);
        add_schedule_table.add_widget(end_time_label);
        add_schedule_table.add_widget(venue_label);
        add_schedule_table.add_widget(date);
        add_schedule_table.add_widget(start_time);
        add_schedule_table.add_widget(end_time);
        add_schedule_table.add_widget(venue);
        add_schedule_table.add_widget(add_button);
        
        add_schedule_table.setVisible(true);
        add_schedule_table.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        add_schedule_table.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Tutor_Menu.menu();
                add_schedule_table.dispose();
            }
        }); 
    }
    
    private static void add(Tutor user){
        try {
            String class_id_input = class_id_input_field.get_input().trim();
            String date_input = date.get_input().trim();
            String start_time_input = start_time.get_input().trim();
            String end_time_input = end_time.get_input().trim();
            String venue_input = venue.get_input().trim();
            String tutorID = user.get("id");
            
            /* Check if fields are empty */
            if (class_id_input.isEmpty() || date_input.isEmpty() || start_time_input.isEmpty() || end_time_input.isEmpty() || venue_input.isEmpty()) {
                Message_Frame.message_frame("Input Error", "Please fill in all fields before adding the schedule.");
                return;
            }

            /* Check Class ID format */
            if (!class_id_input.matches("C\\d{3}")) {
                Message_Frame.message_frame("Format Error", "Class ID must be in the format CXXX (e.g., C001).");
                return;
            }

            /* Check Date Format */
            DateTimeFormatter dateFormatter = DateTimeFormatter.ofPattern("dd-MM-yyyy");
            LocalDate parsedDate;
            try {
                parsedDate = LocalDate.parse(date_input, dateFormatter);
            } catch (DateTimeParseException e) {
                Message_Frame.message_frame("Format Error", "Date must be in the format DD-MM-YYYY.");
                return;
            }

            /* Validate Date */
            if (parsedDate.isBefore(LocalDate.now())) {
                Message_Frame.message_frame("Date Error", "Schedule date cannot be in the past.");
                return;
            }

            /* Check Time Format */
            DateTimeFormatter timeFormatter = DateTimeFormatter.ofPattern("HHMM");
            LocalTime parsedStartTime;
            LocalTime parsedEndTime;
            try {
                parsedStartTime = LocalTime.parse(start_time_input, timeFormatter);
                parsedEndTime = LocalTime.parse(end_time_input, timeFormatter);
            } catch (DateTimeParseException e) {
                Message_Frame.message_frame("Format Error", "Start Time and End Time must be in HHMM format (e.g., 0900, 1530).");
                return;
            }

            /* Validate Time */
            if (parsedStartTime.isAfter(parsedEndTime) || parsedStartTime.equals(parsedEndTime)) {
                Message_Frame.message_frame("Time Error", "Start Time must be before End Time.");
                return;
            }

            /* Check to see if ClassID is assigned to tutor */
            String[] tutor_assignment_lines = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor_assignment.txt");
            boolean isClassAssignedToTutor = false;
            for (String assignment_line : tutor_assignment_lines) {
                String[] parts = assignment_line.split(";");

                if (parts.length >= 3 && parts[1].equals(tutorID) && parts[2].equals(class_id_input)) {
                    isClassAssignedToTutor = true;
                    break;
                }
            }

            if (!isClassAssignedToTutor) {
                Message_Frame.message_frame("Validation Error", "The entered Class ID (" + class_id_input + ") is not assigned to your account. Please enter a valid Class ID.");
                return;
            }


            /* Generate Schedule IDs */
            String total_file_path = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt";
            String[] total_list = file.read(total_file_path);
            
            int schedule_total = 0;
            for (String line : total_list){
                String[] parts = line.split(";");
                if (parts.length == 2 && parts[0].equals("schedule")){
                    schedule_total = Integer.parseInt(parts[1]);
                    break;
                }
            }
            
            schedule_total++;
            String schedule_id = String.format("SC%03d", schedule_total); 
            
            /* Data for class_schedule */
            String data = String.join(";", schedule_id, class_id_input, date_input, start_time_input, end_time_input, venue_input);
            String file_path = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_schedule.txt";
            
            /* Add to file */
            add_feature.add_to_file(file_path, data);
            
            /* Update Total */
            update_feature.update_file(total_file_path, "schedule", 1, String.valueOf(schedule_total));
            
            /* Success Message */
            Message_Frame.message_frame("Success", "Schedule " + schedule_id + " has been successfully added!");
            
            /* Refresh Table */
            Class_Schedule.refreshScheduleTable();
            add_schedule_table.dispose();
            
        } catch (Exception e){
            Message_Frame.message_frame("Error", "An error occurred while adding the schedule: " + e.getMessage());
            e.printStackTrace();
        }
    }
}
