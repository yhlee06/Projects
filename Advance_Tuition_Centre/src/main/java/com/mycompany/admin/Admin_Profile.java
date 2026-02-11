package com.mycompany.admin;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;
import com.mycompany.gui.*;

public class Admin_Profile {
    static Base_Frame base;
    static Input_Frame name;
    static Input_Frame contact_number;
    static Input_Frame email;
    static Input_Frame username;
    static Input_Frame password;

    public static void Admin_Profile(Admin user){
        base = new Base_Frame("Profile", 568, 464);
        
        Label_Frame user_profile = new Label_Frame(user.get("name") + "'s Profile", 22, 10, 208, 39);
        user_profile.font(true, 16);

        Label_Frame name_label = new Label_Frame("Name", 22, 60, 83, 26);
        name_label.font(14);
        Label_Frame contact_label = new Label_Frame("Contact Number", 22, 100, 130, 26);
        contact_label.font(14);
        Label_Frame email_label = new Label_Frame("Email", 22, 140, 79, 26);
        email_label.font(14);

        name = new Input_Frame(341, 26, 180, 60);
        name.set_text(user.get("name"));
        contact_number = new Input_Frame(341, 26, 180, 100);
        contact_number.set_text(user.get("contact"));
        email = new Input_Frame(341, 26, 180, 140);
        email.set_text(user.get("email"));

        Label_Frame account_label = new Label_Frame("Account", 22, 190, 128, 39);
        account_label.font(true, 16);
        Label_Frame username_label = new Label_Frame("Username", 22, 230, 128, 26);
        username_label.font(14);
        Label_Frame password_label = new Label_Frame("Password", 22, 270, 128, 26);
        password_label.font(14);

        username = new Input_Frame(341, 26, 180, 230);
        username.set_text(user.get("username"));
        password = new Input_Frame(341, 26, 180, 270);
        password.set_text(user.get("password"));

        Button_Frame update_button = new Button_Frame("UPDATE", 272, 34, 148, 350, e -> update(user));

        base.add_widget(user_profile);
        base.add_widget(name_label);
        base.add_widget(contact_label);
        base.add_widget(email_label);
        base.add_widget(name);
        base.add_widget(contact_number);
        base.add_widget(email);
        base.add_widget(account_label);
        base.add_widget(username_label);
        base.add_widget(password_label);
        base.add_widget(username);
        base.add_widget(password);
        base.add_widget(update_button);
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Admin_Menu.menu();
                base.dispose();
            }
        });
    }

    private static void update(Admin user){
        String[] update_info = {user.get("id"), name.get_input(), contact_number.get_input(), email.get_input(), username.get_input(), password.get_input()};
        user.update(update_info);
        Message_Frame.message_frame("Update Successful", "Your profile has been updated and will be reflected on the next log in.");
    }
}
