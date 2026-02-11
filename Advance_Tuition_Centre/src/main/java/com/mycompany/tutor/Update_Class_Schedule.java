package com.mycompany.tutor;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.time.LocalDate;
import java.time.LocalTime;
import java.time.format.DateTimeFormatter;
import java.time.format.DateTimeParseException;

import javax.swing.JFrame;

import com.mycompany.edit.Read;
import com.mycompany.edit.Update;
import com.mycompany.gui.Base_Frame;
import com.mycompany.gui.Button_Frame;
import com.mycompany.gui.Input_Frame;
import com.mycompany.gui.Label_Frame;
import com.mycompany.gui.Message_Frame;

public class Update_Class_Schedule {
    static Base_Frame Update_Schedule_Frame;
    static Label_Frame class_id_display_field;
    static Label_Frame subject_display_field;
    static Input_Frame date;
    static Input_Frame start_time;
    static Input_Frame end_time;
    static Input_Frame venue;
    static Update update_feature = new Update();
    static Read read_file = new Read();
    static String Selected_ScheduleID;
    static String Selected_ClassID_For_Schedule; 
    
    private static final DateTimeFormatter DATE_FORMATTER = DateTimeFormatter.ofPattern("dd-MM-yyyy");
    private static final DateTimeFormatter TIME_FORMATTER = DateTimeFormatter.ofPattern("HHMM");

    public static void Update_Schedule_Window(Tutor tutor, String[] Schedule_Info){
        if (Schedule_Info == null || Schedule_Info.length < 7){ 
            Message_Frame.message_frame("Selection Error", "Please select a schedule to update.");
            return;
        }
        
        Selected_ScheduleID = Schedule_Info[0];
        Selected_ClassID_For_Schedule = Schedule_Info[1]; 
        
        Update_Schedule_Frame = new Base_Frame("Update Class Schedule", 568, 464);
        
        Label_Frame title = new Label_Frame("Update existing class schedule",22,10,280,39);
        title.font(true,16);
        
        Label_Frame schedule_id_label = new Label_Frame("Schedule ID", 22, 60, 100, 26);
        schedule_id_label.font(14);
        Label_Frame schedule_id_display = new Label_Frame(Selected_ScheduleID, 180, 60, 180, 26);
        schedule_id_display.font(14);

        Label_Frame class_id_label = new Label_Frame("Class ID", 22, 100, 83, 26);
        class_id_label.font(14);
        class_id_display_field = new Label_Frame(Selected_ClassID_For_Schedule != null ? Selected_ClassID_For_Schedule : "", 180, 100, 180, 26); 
        class_id_display_field.font(14);

        Label_Frame subject_label = new Label_Frame("Subject", 22, 140, 150, 26);
        subject_label.font(14);
        subject_display_field = new Label_Frame(Schedule_Info[2] != null ? Schedule_Info[2] : "", 180, 140, 180, 26);
        subject_display_field.font(14);

        Label_Frame date_label = new Label_Frame("Date (DD-MM-YYYY)", 22, 180, 150, 26);
        date_label.font(14);
        Label_Frame start_time_label = new Label_Frame("Start Time (HHMM)", 22, 220, 150, 26);
        start_time_label.font(14);
        Label_Frame end_time_label = new Label_Frame("End Time (HHMM)", 22, 260, 150, 26);
        end_time_label.font(14); 
        Label_Frame venue_label = new Label_Frame("Venue", 22, 300, 79, 26);
        venue_label.font(14);
        

        date = new Input_Frame(180, 26, 180, 180);
        date.set_text(Schedule_Info[3]); 
        start_time = new Input_Frame(180, 26, 180, 220);
        start_time.set_text(Schedule_Info[4]); 
        end_time = new Input_Frame(180, 26, 180, 260);
        end_time.set_text(Schedule_Info[5]); 
        venue = new Input_Frame(180, 26, 180, 300);
        venue.set_text(Schedule_Info[6]); 
        
        Button_Frame update_button = new Button_Frame("UPDATE", 150, 34, 380, 380, e -> update_schedule(tutor));
        
        Update_Schedule_Frame.add_widget(title);
        Update_Schedule_Frame.add_widget(schedule_id_label);
        Update_Schedule_Frame.add_widget(schedule_id_display);
        Update_Schedule_Frame.add_widget(class_id_label);
        Update_Schedule_Frame.add_widget(class_id_display_field);
        Update_Schedule_Frame.add_widget(subject_label);
        Update_Schedule_Frame.add_widget(subject_display_field);
        Update_Schedule_Frame.add_widget(date_label);
        Update_Schedule_Frame.add_widget(start_time_label);
        Update_Schedule_Frame.add_widget(end_time_label);
        Update_Schedule_Frame.add_widget(venue_label);
        Update_Schedule_Frame.add_widget(date);
        Update_Schedule_Frame.add_widget(start_time);
        Update_Schedule_Frame.add_widget(end_time);
        Update_Schedule_Frame.add_widget(venue);
        Update_Schedule_Frame.add_widget(update_button);
        
        Update_Schedule_Frame.setVisible(true);
        Update_Schedule_Frame.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        Update_Schedule_Frame.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Tutor_Menu.menu();
                Update_Schedule_Frame.dispose();
            }
        }); 
    } 
    
    static void update_schedule(Tutor tutor){
        try {
            String class_id_from_schedule = Selected_ClassID_For_Schedule; 
            
            String date_input = date.get_input().trim();
            String start_time_input = start_time.get_input().trim();
            String end_time_input = end_time.get_input().trim();
            String venue_input = venue.get_input().trim();
            String tutorID = tutor.get("id");
            
            /* Input Validation */
            if (date_input.isEmpty() || start_time_input.isEmpty() || end_time_input.isEmpty() || venue_input.isEmpty()) {
                Message_Frame.message_frame("Input Error", "Please fill in all fields before updating the schedule.");
                return;
            }

            /* Format Validation */
            LocalDate parsedDate;
            try {
                parsedDate = LocalDate.parse(date_input, DATE_FORMATTER);
            } catch (DateTimeParseException e) {
                Message_Frame.message_frame("Format Error", "Date must be in the format DD-MM-YYYY.");
                return;
            }

            if (parsedDate.isBefore(LocalDate.now())) {
                Message_Frame.message_frame("Date Error", "Schedule date cannot be in the past.");
                return;
            }

            LocalTime parsedStartTime;
            LocalTime parsedEndTime;
            try {
                parsedStartTime = LocalTime.parse(start_time_input, TIME_FORMATTER);
                parsedEndTime = LocalTime.parse(end_time_input, TIME_FORMATTER);
            } catch (DateTimeParseException e) {
                Message_Frame.message_frame("Format Error", "Start Time and End Time must be in HHMM format (e.g., 0900, 1530).");
                return;
            }

            if (parsedStartTime.isAfter(parsedEndTime) || parsedStartTime.equals(parsedEndTime)) {
                Message_Frame.message_frame("Time Error", "Start Time must be before End Time.");
                return;
            }

            /* Validate if the Class ID (from the schedule) is still assigned to the current tutor */
            String[] tutor_assignment_lines = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor_assignment.txt");
            boolean isClassAssignedToTutor = false;
            for (String assignment_line : tutor_assignment_lines) {
                String[] parts = assignment_line.split(";");
                if (parts.length >= 3 && parts[1].equals(tutorID) && parts[2].equals(class_id_from_schedule)) {
                    isClassAssignedToTutor = true;
                    break;
                }
            }

            if (!isClassAssignedToTutor) {
                Message_Frame.message_frame("Authorization Error", "You are not authorized to modify schedules for Class ID: " + class_id_from_schedule);
                return;
            }
 

            String classScheduleFilePath = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_schedule.txt";
            

            update_feature.update_file(classScheduleFilePath, Selected_ScheduleID, 2, date_input);
            update_feature.update_file(classScheduleFilePath, Selected_ScheduleID, 3, start_time_input);
            update_feature.update_file(classScheduleFilePath, Selected_ScheduleID, 4, end_time_input); 
            update_feature.update_file(classScheduleFilePath, Selected_ScheduleID, 5, venue_input); 
            
            Message_Frame.message_frame("Update Success", "Schedule " + Selected_ScheduleID + " has been successfully updated.");
            
            Class_Schedule.refreshScheduleTable();
            
            Update_Schedule_Frame.dispose();
            
        } catch (Exception e) {
            Message_Frame.message_frame("Update Error", "An error occurred while updating the schedule: " + e.getMessage());
            e.printStackTrace();
        }
    }
}
