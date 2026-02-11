package com.mycompany.admin;

import com.mycompany.edit.*;
import com.mycompany.gui.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;

public class Tutor_Assignment {
    static Base_Frame base;
    static Table_Frame table;
    static Read file = new Read();
    static String[] tutor_ids;
    static String[] subject_ids;

    public static void Tutor_Assignment(){
        base = new Base_Frame("Tutor Assignment", 910, 500);
        
        Label_Frame title = new Label_Frame("Tutor Assignment", 30, 30, 280, 35);
        title.font(true, 25);
        
        Button_Frame assign_button = new Button_Frame("Assign", 164, 35, 323, 30, e -> select("assign"));
        Button_Frame update_button = new Button_Frame("Save Update", 164, 35, 507, 30, e -> select("update"));
        Button_Frame delete_button = new Button_Frame("Delete", 164, 35, 691, 30, e -> select("delete"));
        table = new Table_Frame(800, 350, 55, 80);

        base.add_widget(title);
        base.add_widget(table);
        base.add_widget(assign_button);
        base.add_widget(update_button);
        base.add_widget(delete_button);

        display_data();

        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Admin_Menu.menu();
                base.dispose();
            }
        });
    }

    public static void display_data(){
        String[] column = {"ID", "Tutor", "Class", "Level", "Subject"};
        String[] assign_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor_assignment.txt");
        String[] tutor_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor.txt");
        String[] class_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt");
        
        String[][] table_data = new String[assign_data.length][5];
        String[] data;
        String[] tutor;
        String[] subject;

        tutor_ids = new String[assign_data.length];
        subject_ids = new String[assign_data.length];

        for (int row = 0; row < assign_data.length; row++){
            data = assign_data[row].split(";");
            table_data[row][0] = data[0];
            for (int index = 0; index < tutor_data.length; index++){
                tutor = tutor_data[index].split(";");
                if (tutor[0].equals(data[1])){
                    table_data[row][1] = tutor[1];
                    tutor_ids[row] = data[1];
                    break;
                }
            }
            for (int index = 0; index < class_data.length; index++) {
                subject = class_data[index].split(";");
                if (subject[0].equals(data[2])){
                    table_data[row][2] = subject[2];
                    subject_ids[row] = data[2];
                    break;
                }
            }
            table_data[row][3] = data[3];
            table_data[row][4] = data[4];
        }

        table.refresh_data(column, table_data);
    }

    private static void select(String selection) {
        switch (selection){
            case "assign" :
                base.setVisible(false);
                Assign_Tutor.Assign_Tutor();
                break;
            case "update" :
                Update update_file = new Update();
                String[][] assign_data = table.get_data(new int[] {0, 1, 2, 3, 4});
                for (int index = 0; index < assign_data.length; index++){
                    assign_data[index][1] = tutor_ids[index];
                    assign_data[index][2] =  subject_ids[index];
                }
                update_file.clear_and_update("Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor_assignment.txt", assign_data);
                Message_Frame.message_frame("Update Successful", "Successfully saved updates.");
                display_data();
                break;
            case "delete" :
                Delete delete = new Delete();
                String assign_id = Message_Frame.input_frame("Remove Tutor", "Please enter ID.");
                if (assign_id == null || assign_id.equals("")){
                    break;
                }

                boolean confirm = Message_Frame.confirm_frame("Confirm Deletion", String.format("Do you really want to delete this tutor (%s)?", assign_id));
                if (confirm == true){
                    delete.delete_data("Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor_assignment.txt", assign_id);
                    Message_Frame.message_frame("Deletion Successful", "Successfully deleted assignment.");
                }
                display_data();
                break;
        }
    }

    public static void menu(){
        base.setVisible(true);
        display_data();
    }
}
