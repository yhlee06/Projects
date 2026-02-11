package com.mycompany.tutor;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;

import javax.swing.JFrame;

import com.mycompany.edit.Update;
import com.mycompany.gui.Base_Frame;
import com.mycompany.gui.Button_Frame;
import com.mycompany.gui.Input_Frame;
import com.mycompany.gui.Label_Frame;
import com.mycompany.gui.Message_Frame;

public class Update_Class_Info {
    static Base_Frame Update_Class_Frame;
    static Input_Frame subject;
    static Input_Frame year;
    static Input_Frame price;
    static Input_Frame status;
    static Update update = new Update();
    static String Selected_ClassID;

    public static void Update_Class_Window(Tutor tutor, String[] Class_Info){
        if (Class_Info == null){
            Message_Frame.message_frame("Selection Error", "Please select a class.");
            return;
        }

    Selected_ClassID = Class_Info[0];

            /* Labels */
        Update_Class_Frame = new Base_Frame("Update Class Info", 568, 464);
        Label_Frame title = new Label_Frame("Update existing class information",22,10,208,39);
        title.font(true,16);
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
        subject.set_text(Class_Info[1]);
        year = new Input_Frame(180, 26, 180, 100);
        year.set_text(Class_Info[2]);
        price = new Input_Frame(180, 26, 180, 140);
        price.set_text(Class_Info[3]);
        status = new Input_Frame(180, 26, 180, 180);
        status.set_text(Class_Info[4]);

        /* Update Button */
        Button_Frame update_button = new Button_Frame("UPDATE", 150, 34, 380, 380, e -> update_class(tutor));

        Update_Class_Frame.add_widget(title);
        Update_Class_Frame.add_widget(subject_label);
        Update_Class_Frame.add_widget(year_label);
        Update_Class_Frame.add_widget(price_label);
        Update_Class_Frame.add_widget(status_label);
        Update_Class_Frame.add_widget(subject);
        Update_Class_Frame.add_widget(year);
        Update_Class_Frame.add_widget(price);
        Update_Class_Frame.add_widget(status);
        Update_Class_Frame.add_widget(update_button);
        Update_Class_Frame.setVisible(true);
        Update_Class_Frame.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        Update_Class_Frame.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Tutor_Menu.menu();
                Update_Class_Frame.dispose();
            }
        }); 
    }      

    static void update_class(Tutor user){
        try {
            /* Update Data */
            update.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt", Selected_ClassID, 2, subject.get_input()); /* Subject */
            update.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt", Selected_ClassID, 3, year.get_input());    /* Year */
            update.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt", Selected_ClassID, 4, price.get_input());   /* Price */
            update.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt", Selected_ClassID, 5, status.get_input());  /* Status */
            
            /* Success Message & Refresh Table */
            Message_Frame.message_frame("Update Success", "Class information has been successfully updated.");
            Class_Info.refreshClassTable(); 
            Update_Class_Frame.dispose();
            
        } catch (Exception e) {
            Message_Frame.message_frame("Update Error", "An error occurred while updating the class information: " + e.getMessage());
        }
    }
}
