package com.mycompany.tutor;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;

import javax.swing.JFrame;

import com.mycompany.gui.Base_Frame;
import com.mycompany.gui.Button_Frame;
import com.mycompany.gui.Input_Frame;
import com.mycompany.gui.Label_Frame;
import com.mycompany.gui.Message_Frame;


public class Tutor_Profile {

    static Base_Frame base;
    static Input_Frame name;
    static Input_Frame nric;
    static Input_Frame email;
    static Input_Frame contact_number;
    static Input_Frame qualification;
    static Input_Frame username;
    static Input_Frame password;

        public static void Tutor_Profile(Tutor user){
        base = new Base_Frame("Profile", 568, 464);
        
            /* Labels */
        Label_Frame user_profile = new Label_Frame(user.get("name") + "'s Profile", 22, 10, 208, 39);
        user_profile.font(true, 16);
        Label_Frame name_label = new Label_Frame("Name", 22, 60, 83, 26);
        name_label.font(14);
        Label_Frame nric_label = new Label_Frame("NRIC", 22, 100, 79, 26);
        nric_label.font(14);
        Label_Frame contact_label = new Label_Frame("Contact Number", 22, 140, 130, 26);
        contact_label.font(14);
        Label_Frame email_label = new Label_Frame("Email", 22, 180, 79, 26);
        email_label.font(14);
        Label_Frame qualification_label = new Label_Frame("Qualification", 22, 220, 79, 26);
        qualification_label.font(14);
        Label_Frame account_label = new Label_Frame("Account", 22, 260, 128, 39);
        account_label.font(true, 16);
        Label_Frame username_label = new Label_Frame("Username", 22, 300, 128, 26);
        username_label.font(14);
        Label_Frame password_label = new Label_Frame("Password", 22, 350, 128, 26);
        password_label.font(14);

            /* Input Frames */
        name = new Input_Frame(180, 26, 180, 60);
        name.set_text(user.get("name"));
        nric = new Input_Frame(180,26,180,100);
        nric.set_text(user.get("nric"));
        contact_number = new Input_Frame(180, 26, 180, 140);
        contact_number.set_text(user.get("contact"));
        email = new Input_Frame(180, 26, 180, 180);
        email.set_text(user.get("email"));
        qualification = new Input_Frame(180,26,180,220);
        qualification.set_text(user.get("qualification"));
        username = new Input_Frame(180, 26, 180, 300);
        username.set_text(user.get("username"));
        password = new Input_Frame(180, 26, 180, 350);
        password.set_text(user.get("password"));

            /* Update Button */
        Button_Frame update_button = new Button_Frame("UPDATE", 150, 34, 380, 380, e -> update(user));

        base.add_widget(user_profile);
        base.add_widget(nric_label);
        base.add_widget(name_label);
        base.add_widget(contact_label);
        base.add_widget(email_label);
        base.add_widget(qualification_label);
        base.add_widget(name);
        base.add_widget(nric);
        base.add_widget(contact_number);
        base.add_widget(email);
        base.add_widget(qualification);
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
                Tutor_Menu.menu();
                base.dispose();
            }
        });
        
    }
        static void update(Tutor user){
        String[] update_info = {user.get("id"),name.get_input(), nric.get_input(), contact_number.get_input(), email.get_input(), qualification.get_input(), username.get_input(), password.get_input()};
        user.update(update_info);
    
        Message_Frame.message_frame("Update Success", "Your Data Has Been Successfully Updated.");

    }
}
