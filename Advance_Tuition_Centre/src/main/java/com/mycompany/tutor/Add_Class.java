
package com.mycompany.tutor;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;

import javax.swing.JFrame;

import com.mycompany.edit.Add;
import com.mycompany.edit.Read;
import com.mycompany.edit.Update;
import com.mycompany.gui.Base_Frame;
import com.mycompany.gui.Button_Frame;
import com.mycompany.gui.Input_Frame;
import com.mycompany.gui.Label_Frame;
import com.mycompany.gui.Message_Frame;

public class Add_Class {
    static Base_Frame add_class_table;
    static Input_Frame subject;
    static Input_Frame year;
    static Input_Frame price;
    static Input_Frame status;
    static Add add_feature = new Add();
    static Read file = new Read();
    static Update update_feature = new Update();
        
    public static void Add_Class(Tutor user){

        /* Base Frame and Title */
        add_class_table = new Base_Frame("Add Class Table", 568, 464);
        Label_Frame title = new Label_Frame("Enter new class data",22,10,208,39);
        title.font(true,16);

        /* Labels */
        Label_Frame subject_label = new Label_Frame("Subject", 22, 60, 83, 26);
        subject_label.font(14);
        Label_Frame year_label = new Label_Frame("Year", 22, 100, 52, 26);
        year_label.font(14);
        Label_Frame price_label = new Label_Frame("Price", 22, 140, 52, 26);
        price_label.font(14);
        Label_Frame status_label = new Label_Frame("Status", 22, 180,79, 26);
        status_label.font(14);

        /* Input Frames */
        subject = new Input_Frame(180, 26, 180, 60);
        year = new Input_Frame(180, 26, 180, 100);
        price = new Input_Frame(180, 26, 180, 140);
        status = new Input_Frame(180, 26, 180, 180);
        
        /* Add Button */
        Button_Frame add_button = new Button_Frame("Add",150,34,380,380,e -> add(user));
        
        add_class_table.add_widget(title);
        add_class_table.add_widget(subject_label);
        add_class_table.add_widget(year_label);
        add_class_table.add_widget(price_label);
        add_class_table.add_widget(status_label);
        add_class_table.add_widget(subject);
        add_class_table.add_widget(year);
        add_class_table.add_widget(price);
        add_class_table.add_widget(status);
        add_class_table.add_widget(add_button);
        
        add_class_table.setVisible(true);
        add_class_table.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        add_class_table.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Tutor_Menu.menu();
                add_class_table.dispose();
            }
        });
    }
    
    private static void add(Tutor user){
        try {
            String subject_input = subject.get_input();
            String year_input = year.get_input();
            String price_input = price.get_input();
            String status_input = status.get_input();

            /*Check if fields are empty */
            if (subject_input.isEmpty() || year_input.isEmpty() || price_input.isEmpty() || status_input.isEmpty()) {
             Message_Frame.message_frame("Input Error", "Please fill in all fields before adding the class.");
            return; 
            }

            String date_created = new java.text.SimpleDateFormat("dd-MM-yyyy").format(new java.util.Date());
            String tutorID = user.get("id");
            
            /*Generate Class IDs */
            String total_file_path = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt";
            String[] total_list = file.read(total_file_path);
            
            int class_total = 0;
            int assignment_total = 0;
            
            for (String line : total_list){
                String[] parts = line.split(";");
                if (parts.length == 2) {
                    if (parts[0].equals("class")){
                        class_total = Integer.parseInt(parts[1]);
                    } else if (parts[0].equals("assignment")){
                        assignment_total = Integer.parseInt(parts[1]);
                    }
                }
            }
            
            class_total++;
            assignment_total++;

            String class_id = String.format("C%03d", class_total);
            String assignment_id = String.format("A%03d", assignment_total);
            
            /* Data for class_information */
            String class_data = String.join(";", class_id, tutorID, subject_input, year_input, price_input, status_input, date_created);
            String class_info_file_path = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt";
            
            /* Data for tutor_assignment */
            String assignment_data = String.join(";", assignment_id, tutorID, class_id, year_input, subject_input);
            String tutor_assign_file_path = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor_assignment.txt";

            /* Add to files */
            add_feature.add_to_file(class_info_file_path, class_data);
            add_feature.add_to_file(tutor_assign_file_path, assignment_data);
            
            /* Update Total */
            update_feature.update_file(total_file_path, "class", 1, String.valueOf(class_total));
            update_feature.update_file(total_file_path, "assignment", 1, String.valueOf(assignment_total));
            
            /* Success Message */
            Message_Frame.message_frame("Success", "Class " + class_id + " has been successfully added and assigned!");
            
            /* Refresh Table */
            Class_Info.refreshClassTable();
            add_class_table.dispose();
            
        } catch (Exception e){
            Message_Frame.message_frame("Error", "An error occurred while adding the class: " + e.getMessage());
            e.printStackTrace();
        }
    }
}

