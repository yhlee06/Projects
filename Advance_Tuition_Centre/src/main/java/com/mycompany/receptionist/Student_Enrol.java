package com.mycompany.receptionist; /**Package containing the class.*/

/**Import API and classes needed.*/
import com.mycompany.edit.*;
import com.mycompany.gui.Message_Frame;
import java.time.YearMonth;
import java.util.*;

public class Student_Enrol extends Details {
    /**Variable, array and objects used.*/
    String id;
    String[] enrol_ids;
    Read file = new Read();
    Read_One one_line = new Read_One();

    /**Constructor for student enrolment.*/
    public Student_Enrol(String student_id){
        id = student_id;
    }

    /**Method to retrieve the ids of all enrolments.*/
    private void get_ids(){
        String[] enrolments = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt");
        ArrayList<String> enrol = new ArrayList<String>();
        for (String enrolment : enrolments){
            String[] data = enrolment.split(";");
            if (data[1].equals(id)){
                enrol.add(data[0]);
            }
        }
        enrol_ids = enrol.toArray(new String[0]);
    }

    /**Method to get information about the student's enrolment.*/
    public String get(String info){
        get_ids();
        if (enrol_ids == null){
            System.out.println("Here");
            return "";
        }
        String[] temporary = new String[enrol_ids.length];
        switch (info){
            case "id":
                return String.join(";", enrol_ids);
            case "name":
                for (int index = 0; index < enrol_ids.length; index++){
                    String class_id = one_line.read(enrol_ids[index], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt")[2];
                    temporary[index] = one_line.read(class_id, "Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt")[2];
                }
                return String.join(";", temporary);
            case "start":
                for (int index = 0; index < enrol_ids.length; index++){
                    temporary[index] = one_line.read(enrol_ids[index], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt")[3];
                }
                return String.join(";", temporary);
            case "end":
                for (int index = 0; index < enrol_ids.length; index++){
                    temporary[index] = one_line.read(enrol_ids[index], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt")[4];
                }
                return String.join(";", temporary);
            default: return "";
        }
    }

    /**Method to update student enrolment.*/
    public void update(String[] info){
        Update update_file = new Update();
        try{
            YearMonth check = YearMonth.parse(info[1]);
            if (!info[2].equals("null")){
                check = YearMonth.parse(info[2]);
            }
        } catch (Exception e){
            Message_Frame.message_frame("Error", "Invalid format please follow. yyyy-mm");
            return;
        }
        update_file.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt", info[0], 3, info[1]);
        update_file.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt", info[0], 4, info[2]);
    }

    /**Method for searching through the information.*/
    public boolean search(String word){
        boolean flag = false;
        get_ids();
        for (String enrol : enrol_ids){
            String[] current = one_line.read(enrol, "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt");
            String[] class_data = one_line.read(current[2], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt");
            ArrayList<String> name = new ArrayList<>(Arrays.asList(class_data[2].split(" ")));
            flag = name.contains(word) || current[0].equals(word) || current[3].equals(word) || current[4].equals(word);
            if (flag == true){
                return flag;
            }
        }
        return flag;
    }
}
