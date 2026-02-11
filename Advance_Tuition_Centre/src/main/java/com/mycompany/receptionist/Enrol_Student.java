package com.mycompany.receptionist; /**Package that contains the class.*/

/**Import API and classes needed.*/
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.time.YearMonth;
import java.util.ArrayList;
import com.mycompany.edit.*;
import com.mycompany.gui.*;
import javax.swing.JFrame;

public class Enrol_Student {
    /**Declare variables used in the class.*/
    static Base_Frame base;
    static Dropdown_Frame class_input;
    static Input_Frame month_input;
    static String student_id;
    static Read file = new Read();

    public static void Enrol_Student(String id){
        /**Creates the base window.*/
        base = new Base_Frame("Enrol Student", 584, 260);
        student_id = id;

        /**Creates the labels.*/
        Label_Frame id_label = new Label_Frame("Student ID", 22, 40, 177, 26);
        id_label.font(14);
        base.add_widget(id_label);
        Label_Frame student_id_label = new Label_Frame(student_id, 199, 40, 100, 26);
        student_id_label.font(14);
        base.add_widget(student_id_label);
        Label_Frame class_label = new Label_Frame("Class", 22, 80, 177, 26);
        class_label.font(14);
        base.add_widget(class_label);
        Label_Frame month_label = new Label_Frame("Enrol Month", 22, 120, 177, 26);
        month_label.font(14);
        base.add_widget(month_label);

        /**Creates the dropdown option for the subjects.*/
        String[] class_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt");
        ArrayList<String> temporary = new ArrayList<>();
        String[] subject;
        for (int index = 0; index < class_data.length; index++){
            subject = class_data[index].split(";");
            if (subject[5].equals("open")){
                temporary.add(String.format("%s %s", subject[0], subject[2]));
            }
        }
        String[] class_name = temporary.toArray(new String[0]);
        
        /**Creates the input frames - dropdown and text field.*/
        class_input = new Dropdown_Frame(341, 26, 199, 80, class_name);
        base.add_widget(class_input);
        month_input = new Input_Frame(341, 26, 199, 120);
        month_input.set_text("yyyy-mm");
        base.add_widget(month_input);

        /**Creates the button.*/
        Button_Frame enrol_button = new Button_Frame("ENROL", 272, 34, 156, 160, e -> enrol());
        base.add_widget(enrol_button);

        /**Code for closing the window and linking to the previous section to be visible.*/
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                base.dispose();
            }
        });
    }

    /**Method to enrol the student into a class.*/
    private static void enrol(){
        String[] subject = class_input.selection().split(" ");
        String month = month_input.get_input();

        /**Count how many classes the student is actively enrolled in.*/
        int count = 0;
        String[] enrolments = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt");
        for (int index = 0; index < enrolments.length; index++){
            String[] current = enrolments[index].split(";");
            if (current[1].equals(student_id) && current[4].equals("null")){
                count = count + 1;
            }
        }

        /**Checks if the student already enrolled in 3 classes (maximum).*/
        if (count == 3){
            Message_Frame.message_frame("Warning", "Student is active in 3 classes.");
            return;
        }

        /**Checks the month year format.*/
        try{
            YearMonth check = YearMonth.parse(month);
        } catch (Exception e){
            Message_Frame.message_frame("Error", "Invalid format please follow. yyyy-mm");
            return;
        }

        /**Add enrolment.*/
        Add add_data = new Add();
        Update update_total = new Update();
        String[] total_list = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt");
        String[] current;
        int enrol_total = 0 ;
        for (int i = 0; i < total_list.length; i++){
            current = total_list[i].split(";");
            if (current[0].equals("enrol")){
                enrol_total = Integer.parseInt(current[1]) + 1;
            }
        }
        String enrol_id = String.format("E%03d", enrol_total);

        String new_data = String.format("%s;%s;%s;%s;null", enrol_id, student_id, subject[0], month);
        add_data.add_to_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt", new_data);
        update_total.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt", "enrol", 1, String.valueOf(enrol_total));

        /**Redirect back to student details.*/
        Student_Details.display_enrolment();
        base.dispose();
    }
}
