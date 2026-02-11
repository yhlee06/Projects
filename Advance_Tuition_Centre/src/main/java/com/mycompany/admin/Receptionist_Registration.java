package com.mycompany.admin;

import com.mycompany.edit.*;
import com.mycompany.gui.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;

public class Receptionist_Registration {
    static Base_Frame base;
    static Input_Frame input_name;
    static Input_Frame input_phone;
    static Input_Frame input_email;
    static Input_Frame input_username;
    static Input_Frame input_password;

    public static void Receptionist_Registration(){
        base = new Base_Frame("Receptionist Registration", 1170, 464);

        Label_Frame register_label = new Label_Frame("Register Receptionist", 22, 10, 208, 39);
        register_label.font(true, 16);
        Label_Frame account_label = new Label_Frame("Account", 22, 200, 128, 39);
        account_label.font(true, 16);

        Label_Frame name_label = new Label_Frame("Name", 22, 60, 177, 26);
        name_label.font(14);
        Label_Frame phone_label = new Label_Frame("Phone Number", 562, 60, 177, 26);
        phone_label.font(14);
        Label_Frame email_label = new Label_Frame("Email", 22, 100, 177, 26);
        email_label.font(14);
        Label_Frame username_label = new Label_Frame("Username", 22, 240, 177, 26);
        username_label.font(14);
        Label_Frame password_label = new Label_Frame("Password", 22, 280, 177, 26);
        password_label.font(14);
        
        input_name = new Input_Frame(341, 26, 199, 60);
        input_phone = new Input_Frame(341, 26, 739, 60);
        input_email = new Input_Frame(341, 26, 199, 100);
        input_username = new Input_Frame(341, 26, 199, 240);
        input_password = new Input_Frame(341, 26, 199, 280);

        Button_Frame register_button = new Button_Frame("REGISTER", 272, 34, 449, 350, e -> register()); 

        base.add_widget(register_label);
        base.add_widget(name_label);
        base.add_widget(input_name);
        base.add_widget(phone_label);
        base.add_widget(input_phone);
        base.add_widget(email_label);
        base.add_widget(input_email);
        
        base.add_widget(account_label);
        base.add_widget(username_label);
        base.add_widget(input_username);
        base.add_widget(password_label);
        base.add_widget(input_password);
        base.add_widget(register_button);

        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Receptionist_Management.menu();
                base.dispose();
            }
        });
    }

    private static void register(){
        Read file = new Read();
        Add add_receptionist = new Add();
        Update update_total = new Update();

        String[] total_list = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt");
        String[] current;
        int receptionist_total = 0 ;
        for (int i = 0; i < total_list.length; i++){
            current = total_list[i].split(";");
            if (current[0].equals("receptionist")){
                receptionist_total = Integer.parseInt(current[1]) + 1;
            }
        }
        String receptionist_id = String.format("R%03d", receptionist_total);
        String new_receptionist = String.format("%s;%s;%s;%s", receptionist_id, input_name.get_input(), input_phone.get_input(), input_email.get_input());
        String new_account = String.format("%s;%s;%s;tutor", receptionist_id, input_username.get_input(), input_password.get_input());

        add_receptionist.add_to_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/receptionist.txt", new_receptionist);
        add_receptionist.add_to_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt", new_account);
        update_total.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt", "receptionist", 1, String.valueOf(receptionist_total));

        base.dispose();
        Receptionist_Management.display_data();
        Receptionist_Management.menu();
    }
}
