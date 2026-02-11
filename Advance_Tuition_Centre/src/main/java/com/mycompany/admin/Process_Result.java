package com.mycompany.admin;

import com.mycompany.edit.*;
import com.mycompany.gui.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;

public class Process_Result {
    static Base_Frame base;
    static Dropdown_Frame student_id_input;
    static Input_Frame Add_Math_input;
    static Input_Frame Math_input;
    static Input_Frame English_input;
    static Input_Frame Bahasa_Malaysia_input;
    static Input_Frame Mandarine_input;
    static Input_Frame Chemistry_input;
    static Input_Frame Biology_input;
    static Input_Frame Physic_input;
    static Input_Frame History_input;
    static Read file = new Read();

    public static void Process_Result(){
        base = new Base_Frame("Student Result", 800, 600);

        Label_Frame student_id_label = new Label_Frame("Student ID", 22, 40, 177, 26);
        student_id_label.font(14);
        Label_Frame Add_Math_label = new Label_Frame("Add_Math", 22, 120, 177, 26);
        Add_Math_label.font(14);
        Label_Frame Math_label = new Label_Frame("Math", 22, 160, 177, 26);
        Math_label.font(14);
        Label_Frame English_label = new Label_Frame("English", 22, 200, 177, 26);
        English_label.font(14);
        Label_Frame Bahasa_Malaysia_label = new Label_Frame("Bahasa_Malaysia", 22, 240, 177, 26);
        Bahasa_Malaysia_label.font(14);
        Label_Frame Mandarine_label = new Label_Frame("Mandarine", 22, 280, 177, 26);
        Mandarine_label.font(14);
        Label_Frame Chemistry_label = new Label_Frame("Chemistry", 22, 320, 177, 26);
        Chemistry_label.font(14);
        Label_Frame Biology_label = new Label_Frame("Biology", 22, 360, 177, 26);
        Biology_label.font(14);
        Label_Frame Physic_label = new Label_Frame("Physic", 22, 400, 177, 26);
        Physic_label.font(14);
        Label_Frame History_label = new Label_Frame("History", 22, 440, 177, 26);
        History_label.font(14);
        Label_Frame level_label = new Label_Frame("level", 22, 480, 177, 26);
        level_label.font(14);

        String[] student_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
        String[] student_id = new String[student_data.length];
        String[] student;
        for (int index = 0; index < student_data.length; index++){
            student = student_data[index].split(";");
            student_id[index] = student[0];
        }
        
        student_id_input = new Dropdown_Frame(341, 26, 199, 40, student_id);
        Add_Math_input = new Input_Frame(341, 26, 199, 120);
        Math_input = new Input_Frame(341, 26, 199, 160);
        English_input = new Input_Frame(341, 26, 199, 200);
        Bahasa_Malaysia_input = new Input_Frame(341, 26, 199, 240);
        Mandarine_input = new Input_Frame(341, 26, 199, 280);
        Chemistry_input = new Input_Frame(341, 26, 199, 320);
        Biology_input = new Input_Frame(341, 26, 199, 360);
        Physic_input = new Input_Frame(341, 26, 199, 400);
        History_input = new Input_Frame(341, 26, 199, 440);

        Button_Frame enrol_button = new Button_Frame("ADD", 272, 34, 156, 520, e -> enrol()); 

        base.add_widget(student_id_label);
        base.add(student_id_input);
        base.add_widget(Add_Math_label);
        base.add_widget(Add_Math_input);
        base.add_widget(Math_label);
        base.add_widget(Math_input);
        base.add_widget(English_label);
        base.add_widget(English_input);
        base.add_widget(Bahasa_Malaysia_label);
        base.add_widget(Bahasa_Malaysia_input);
        base.add_widget(Mandarine_label);
        base.add_widget(Mandarine_input);
        base.add_widget(Chemistry_label);
        base.add_widget(Chemistry_input);
        base.add_widget(Biology_label);
        base.add_widget(Biology_input);
        base.add_widget(Physic_label);
        base.add_widget(Physic_input);
        base.add_widget(History_label);
        base.add_widget(History_input);
        base.add_widget(enrol_button);

        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Result_System.menu();
                base.dispose();
            }
        });
    }

    private static void enrol(){
        String student_id = student_id_input.selection();
        String Add_Math = Add_Math_input.get_input();
        String Math = Math_input.get_input();
        String English = English_input.get_input();
        String Bahasa_Malaysia = Bahasa_Malaysia_input.get_input();
        String Mandarine = Mandarine_input.get_input();
        String Chemistry = Chemistry_input.get_input();
        String Biology = Biology_input.get_input();
        String Physic = Physic_input.get_input();
        String History = History_input.get_input();

        Add add_data = new Add();

        String new_data = String.format("%s;%s;%s;%s;%s;%s;%s;%s;%s;%s;%s", student_id, Add_Math, Math, English, Bahasa_Malaysia, Mandarine, Chemistry, Biology, Physic, History, "5");
        add_data.add_to_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/result.txt", new_data);

        base.dispose();
        Result_System.display_data();
        Result_System.menu();
    }
}

