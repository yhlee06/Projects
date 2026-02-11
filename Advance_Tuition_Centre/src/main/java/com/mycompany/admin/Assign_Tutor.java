package com.mycompany.admin;

import com.mycompany.edit.*;
import com.mycompany.gui.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;

public class Assign_Tutor {
    static Base_Frame base;
    static Dropdown_Frame tutor_id_input;
    static Dropdown_Frame class_input;
    static Input_Frame level_input;
    static Read file = new Read();

    public static void Assign_Tutor(){
        base = new Base_Frame("Assign Tutor", 584, 260);

        Label_Frame tutor_id_label = new Label_Frame("Tutor ID", 22, 40, 177, 26);
        tutor_id_label.font(14);
        Label_Frame class_label = new Label_Frame("Class", 22, 80, 177, 26);
        class_label.font(14);
        Label_Frame level_label = new Label_Frame("Level", 22, 120, 177, 26);
        level_label.font(14);

        String[] tutor_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor.txt");
        String[] tutor_id = new String[tutor_data.length];
        String[] tutor;
        for (int index = 0; index < tutor_data.length; index++){
            tutor = tutor_data[index].split(";");
            tutor_id[index] = tutor[0];
        }

        String[] class_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt");
        String[] class_name = new String[class_data.length];
        String[] subject;
        for (int index = 0; index < class_data.length; index++){
            subject = class_data[index].split(";");
            class_name[index] = String.format("%s %s", subject[0], subject[2]);
        }
        
        tutor_id_input = new Dropdown_Frame(341, 26, 199, 40, tutor_id);
        class_input = new Dropdown_Frame(341, 26, 199, 80, class_name);
        level_input = new Input_Frame(341, 26, 199, 120);

        Button_Frame assign_button = new Button_Frame("ASSIGN", 272, 34, 156, 160, e -> assign()); 

        base.add_widget(tutor_id_label);
        base.add(tutor_id_input);
        base.add_widget(class_label);
        base.add_widget(class_input);
        base.add_widget(level_label);
        base.add_widget(level_input);
        base.add_widget(assign_button);

        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Tutor_Assignment.menu();
                base.dispose();
            }
        });
    }

    private static void assign(){
        String tutor_id = tutor_id_input.selection();
        String[] subject = class_input.selection().split(" ");
        String level = level_input.get_input();

        Add add_data = new Add();
        Update update_total = new Update();
        String[] total_list = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt");
        String[] current;
        int assign_total = 0 ;
        for (int i = 0; i < total_list.length; i++){
            current = total_list[i].split(";");
            if (current[0].equals("assign")){
                assign_total = Integer.parseInt(current[1]) + 1;
            }
        }
        String assign_id = String.format("A%03d", assign_total);

        String new_data = String.format("%s;%s;%s;%s;%s", assign_id, tutor_id, subject[0], level, subject[1] + " " + subject[2] + " " + subject[3]);
        add_data.add_to_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor_assignment.txt", new_data);
        update_total.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt", "assign", 1, String.valueOf(assign_total));


        base.dispose();
        Tutor_Assignment.display_data();
        Tutor_Assignment.menu();
    }
}
