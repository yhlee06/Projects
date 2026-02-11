package com.mycompany.admin;

import com.mycompany.edit.*;
import com.mycompany.gui.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;

public class Receptionist_Management {
    static Base_Frame base;
    static Table_Frame table;
    static Read file = new Read();

    public static void Receptionist_Management(){
        base = new Base_Frame("Receptionist Management", 910, 500);
        
        Label_Frame title = new Label_Frame("Receptionist Management", 30, 30, 280, 35);
        title.font(true, 25);
        
        Button_Frame register_button = new Button_Frame("Register", 164, 35, 323, 30, e -> select("register"));
        Button_Frame update_button = new Button_Frame("Save Update", 164, 35, 507, 30, e -> select("update"));
        Button_Frame delete_button = new Button_Frame("Delete", 164, 35, 691, 30, e -> select("delete"));
        table = new Table_Frame(800, 350, 55, 80);

        base.add_widget(title);
        base.add_widget(table);
        base.add_widget(register_button);
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
        String[] column = {"ID", "Name", "Contact Number", "Email", "Username", "Password"};
        String[] data_file = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/receptionist.txt");
        String[] user_account = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt");
        
        String[][] table_data = new String[data_file.length][8];
        String[] data;
        String[] user;

        for (int i = 0; i < data_file.length; i++){
            data = data_file[i].split(";");
            table_data[i][0] = data[0];
            table_data[i][1] = data[1];
            table_data[i][2] = data[2];
            table_data[i][3] = data[3];
            for (int j = 0; j < user_account.length; j++){
                user = user_account[j].split(";");
                if (data[0].equals(user[0])){
                    table_data[i][4] = user[1];
                    table_data[i][5] = user[2];
                    break;
                }
            }
        }

        table.refresh_data(column, table_data);
    }

    private static void select(String selection){
        switch (selection) {
            case "register" : 
                base.setVisible(false);
                Receptionist_Registration.Receptionist_Registration();
                break;
            case "update" : 
                Update update_file = new Update();
                String[][] receptionist_data = table.get_data(new int[] {0, 1, 2, 3});
                update_file.clear_and_update("Advance_Tuition_Centre/src/main/java/com/mycompany/data/receptionist.txt", receptionist_data);
                String[][] receptionist_account = table.get_data(new int[] {0, 4, 5});
                for (String[] row : receptionist_account){
                    update_file.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt", row[0], 1, row[1]);
                    update_file.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt", row[0], 2, row[2]);
                }
                Message_Frame.message_frame("Update Successful", "Successfully saved updates.");
                display_data();
                break;
            case "delete" :
                Delete delete = new Delete();
                String receptionist_id = Message_Frame.input_frame("Remove Receptionist", "Please enter receptionist ID.");
                if (receptionist_id == null || receptionist_id.equals("")){
                    break;
                }

                boolean confirm = Message_Frame.confirm_frame("Confirm Deletion", String.format("Do you really want to delete this receptionist (%s)?", receptionist_id));
                if (confirm == true){
                    delete.delete_data("Advance_Tuition_Centre/src/main/java/com/mycompany/data/receptionist.txt", receptionist_id);
                    delete.delete_data("Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt", receptionist_id);
                    Message_Frame.message_frame("Deletion Successful", "Successfully deleted receptionist.");
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
