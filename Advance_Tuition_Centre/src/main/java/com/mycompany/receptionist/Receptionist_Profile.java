package com.mycompany.receptionist; /**Package containing the class.*/

/**Import API and classes needed.*/
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;
import com.mycompany.gui.*;
import com.mycompany.edit.*;

public class Receptionist_Profile {
    /**Objects used in the class.*/
    static Base_Frame base;
    static Input_Frame name;
    static Input_Frame contact_number;
    static Input_Frame email;
    static Input_Frame username;
    static Password_Frame password;

    public static void Receptionist_Profile(Receptionist user){
        /**Creates the base window.*/
        base = new Base_Frame("Profile", 568, 464);
        
        /**Profile Label.*/
        Label_Frame user_profile = new Label_Frame(user.get("name") + "'s Profile", 22, 10, 208, 39);
        user_profile.font(true, 16);
        base.add_widget(user_profile);

        /**Label for the details displayed.*/
        Label_Frame name_label = new Label_Frame("Name", 22, 60, 83, 26);
        name_label.font(14);
        base.add_widget(name_label);
        Label_Frame contact_label = new Label_Frame("Contact Number", 22, 100, 130, 26);
        contact_label.font(14);
        base.add_widget(contact_label);
        Label_Frame email_label = new Label_Frame("Email", 22, 140, 79, 26);
        email_label.font(14);
        base.add_widget(email_label);

        /**Display receptionist details. Doubles as an input area for any changes / updates.*/
        name = new Input_Frame(341, 26, 180, 60);
        name.set_text(user.get("name"));
        base.add_widget(name);
        contact_number = new Input_Frame(341, 26, 180, 100);
        contact_number.set_text(user.get("contact"));
        base.add_widget(contact_number);
        email = new Input_Frame(341, 26, 180, 140);
        email.set_text(user.get("email"));
        base.add_widget(email);

        /**Label for the account details displayed.*/
        Label_Frame account_label = new Label_Frame("Account", 22, 190, 128, 39);
        account_label.font(true, 16);
        base.add_widget(account_label);
        Label_Frame username_label = new Label_Frame("Username", 22, 230, 128, 26);
        username_label.font(14);
        base.add_widget(username_label);
        Label_Frame password_label = new Label_Frame("Password", 22, 270, 128, 26);
        password_label.font(14);
        base.add_widget(password_label);

        /**Display account details. Doubles as an input area for any changes / updates.*/
        username = new Input_Frame(341, 26, 180, 230);
        username.set_text(user.get("username"));
        base.add_widget(username);
        password = new Password_Frame(341, 26, 180, 270);
        password.set_text(user.get("password"));
        base.add_widget(password);

        /**Save update button.*/
        Button_Frame update_button = new Button_Frame("UPDATE", 272, 34, 148, 350, e -> update(user));
        base.add_widget(update_button);

        /**Set the window visibility and also redirects back to the main menu of receptionist when closed.*/
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Receptionist_Menu.menu();
                base.dispose();
            }
        });
    }

    /**Private method for updating the receptionist details.*/
    private static void update(Receptionist user){
        Read file = new Read(); /**Object for reading information from a text file.*/

        /**Checks if username is already taken.*/
        String[] accounts = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt");
        for (String account : accounts){
            String[] data = account.split(";");
            if (data[1].equals(username.get_input()) && (!data[0].equals(user.get("id")))){
                /**If username exists, error message displayed to notify the user.*/
                Message_Frame.message_frame("Error", "Username already taken. Please choose another one.");
                return;
            }
        }

        /**Checks if the password has enough characters in it.*/
        if (password.get_input().length() < 8){
            Message_Frame.message_frame("Error", "Password should be minimum 8 characters long.");
            return;
        }

        /**Checks if any input is empty.*/
        String[] update_info = {user.get("id"), name.get_input(), contact_number.get_input(), email.get_input(), username.get_input(), password.get_input()};
        for (String check : update_info){
            if (check.isBlank()){
                Message_Frame.message_frame("Error", "Please fill up all information.");
                return;
            }
        }
        
        /**Updates the information in the text files.*/
        user.update(update_info);
        Message_Frame.message_frame("Update Successful", "Your profile has been updated and will be reflected on the next log in.");
    }
}
