package com.mycompany.main; /**Package containing the main class.*/

/**Import API and classes needed.*/
import com.mycompany.admin.Admin_Menu;
import com.mycompany.edit.Read;
import com.mycompany.edit.Read_One;
import com.mycompany.gui.Base_Frame;
import com.mycompany.gui.Button_Frame;
import com.mycompany.gui.Input_Frame;
import com.mycompany.gui.Label_Frame;
import com.mycompany.gui.Password_Frame;
import com.mycompany.receptionist.Receptionist_Menu;
import com.mycompany.student.Student_Menu;
import com.mycompany.tutor.Tutor_Menu;

/**Main class that is run.*/
public class Main {
    /**Objects and variable used in the class*/
    static Read read_file = new Read();
    static Base_Frame base;
    static Input_Frame input_username;
    static Password_Frame input_password;
    static Button_Frame button_login;
    static Label_Frame attempt_notice;
    static int attempt = 3;

    /**Main runs the log in page which would link to the other sections.*/
    public static void main(String[] args) {
        /**Create base window for the log in.*/
        base = new Base_Frame("Log In", 336, 377);
        
        /**Title label.*/
        Label_Frame title = new Label_Frame("LOG IN", 127, 30, 62, 17);
        title.font(false, 18);
        base.add_widget(title);
        
        /**Username input.*/
        Label_Frame label_username = new Label_Frame("Username", 22, 77, 96, 14);
        label_username.font(16);
        base.add_widget(label_username);
        input_username = new Input_Frame(272, 34, 22, 100);
        base.add_widget(input_username);

        /**Password Input.*/
        Label_Frame label_password = new Label_Frame("Password", 22, 164, 90, 14);
        label_password.font(16);
        base.add_widget(label_password);
        input_password = new Password_Frame(272, 34, 22, 187);
        base.add_widget(input_password);
        
        /**Attempt notice label.*/
        attempt_notice = new Label_Frame("", 22, 230, 200, 14);
        attempt_notice.font(14);
        base.add_widget(attempt_notice);

        /**Log in button.*/
        button_login = new Button_Frame("LOG IN", 272, 34, 22, 251, e -> login());
        base.add_widget(button_login);

        /**Set window visibility to visible.*/
        base.setVisible(true);
    }

    /**Private method accessible only in the class for log in method.*/
    private static void login(){
    	
        /**Stores all the user account information in an array variable.*/
        String[] users = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt");
        /**Stores input values from input frames.*/

        String username = input_username.get_input();
        String password = String.valueOf(input_password.get_input());
        boolean status = false; /**Stores the login status as a boolean value.*/
        String role = new String(); /**Declare string variable to store role of user.*/
        String id = new String(); /**Declare string variable to store the user's id.*/

        /**Loop through each account to check for log in.*/
        for (String user : users){
            String[] user_info = user.split(";"); /**Splits the user information into an array of data.*/
            /**Checks for a match. If no matches, check if the username have a match to determine possible user role.*/
            if (user_info[1].equals(username) && user_info[2].equals(password)){
                status = true; /**Status true means a successful log in.*/
                id = user_info[0]; /**Stores the ID of the user who is going to log in for later use.*/
                role = user_info[3]; /**Stores the role of the user logging in.*/
                break;
            } else if (user_info[1].equals(username)) {
                role = user_info[3]; /**Store the role of the user possibly trying to log in.*/
            }
        }

        /**Checks the status to determine if log in is a success.*/
        if (status == true){ /**Log in successful and can proceed to redirect to the respective menus.*/
            base.setVisible(false); /**Hides the log in page.*/
            /**Find only the data for the user logging in using the read method from Read_One class.*/
            Read_One one_line = new Read_One();

            String[] user_info = one_line.read(id, "Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt");
            /**Switch case as a navigation for redirecting to the respective main menus of each role.*/

            switch (role){
                case "admin": 
                    Admin_Menu.Admin_Menu(user_info[0], user_info[1], user_info[2]);
                    break;
                case "receptionist":
                    Receptionist_Menu.Receptionist_Menu(user_info[0], user_info[1], user_info[2]);
                    break;
                case "tutor":
                    Tutor_Menu.Tutor_Menu(user_info[0], user_info[1], user_info[2]);
                    break;

                case "student":
                	Student_Menu.Student_Menu(user_info[0],  user_info[1],  user_info[2]);
                	break;
            }
        }else{ /**Log in unsuccessful.*/
            /**Checks if the user logging in is a possible admin.*/
            if (role.equals("admin")){
                return; /**Nothing changes as admin is giving unlimited attempts.*/
            }

            /**If not admin. Number of attempts reduces and label for remaning attempts is displayed.*/
            attempt = attempt - 1;
            if (attempt == 0){ /**Disables the button if attempts already used up.*/
                button_login.status(false);
            } else{
                button_login.status(true);
            }
            attempt_notice.text("Attempts left: " + String.valueOf(attempt));
        }
    }

    /**Public method that makes the logging page visible and resets the attempts so that a new user can log in.*/
    public static void exit(){
        base.setVisible(true);
        attempt = 3;
        input_username.set_text(null);
        input_password.set_text(null);
        attempt_notice.text("");
    }
}
