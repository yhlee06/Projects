package com.mycompany.receptionist; /**Package containing the class. */

/**Import API and classes needed.*/
import java.util.ArrayList;
import java.util.Arrays;
import com.mycompany.edit.*;

public class Profile{
    /**Variables storing the student information.*/
    String id;
    String name;
    String ic;
    String email;
    String phone;
    String address;
    String level;

    /**Objects related to student account, enrolment and payment.*/
    Read_One one_line = new Read_One(); /**To read text files.*/
    Details account;
    Details enrolment;
    Student_Payment payment;

    public Profile(String student_id){ /**Constructor*/
        String[] student = one_line.read(student_id, "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
        id = student[0];
        name = student[1];
        ic = student[2];
        email = student[3];
        phone = student[4];
        address = student[5];
        level = student[6];
        account = new Student_Account(student_id);
        enrolment = new Student_Enrol(student_id);
        payment = new Student_Payment(student_id);
    }

    /**To get any profile information.*/
    public String get_profile(String info){
        switch (info){
            case "id" : return id;
            case "name" : return name;
            case "ic" : return ic;
            case "email" : return email;
            case "phone" : return phone;
            case "address" : return address;
            case "level" : return level;
            default : return "";
        }
    }

    /**Method to update profile.*/
    public void update_profile(String[] info){
        Update update_file = new Update();
        String file_path = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt";

        if (!(info[0].equals(name))){
            update_file.update_file(file_path, id, 1, info[0]);
            name = info[0];
        }

        if (!(info[1].equals(ic))){
            update_file.update_file(file_path, id, 2, info[1]);
            ic = info[1];
        }

        if (!(info[2].equals(email))){
            update_file.update_file(file_path, id, 3, info[2]);
            email = info[2];
        }

        if (!(info[3].equals(phone))){
            update_file.update_file(file_path, id, 4, info[3]);
            phone = info[3];
        }

        if (!(info[4].equals(address))){
            update_file.update_file(file_path, id, 5, info[4]);
            address = info[4];
        }
        
        if (!(info[5].equals(level))){
            update_file.update_file(file_path, id, 6, info[5]);
            level = info[5];
        }
    }

    /**To get info about student's account.*/
    public String get_account(String info){
        switch (info){
            case "username" : return account.get("username");
            case "password" : return account.get("password");
            default : return "";
        }
    }

    /**Update account.*/
    public void update_account(String[] info){
        account.update(info);
    }

    /**To get info about the student's enrolment.*/
    public String[] get_enrolment(String info){
        String[] data;
        switch (info){
            case "id":
                data = enrolment.get("id").split(";");
                return data;
            case "name":
                data = enrolment.get("name").split(";");
                return data;
            case "start":
                data = enrolment.get("start").split(";");
                return data;
            case "end":
                data = enrolment.get("end").split(";");
                return data;
            default:return null;
        }
    }

    /**Upate the enrolment.*/
    public void update_enrolment(String[] info){
        for (String line : info){
            String[] data = line.split(";");
            enrolment.update(data);
        }
    }

    /**To get info about their payment.*/
    public String get_payment(String info){
        switch (info){
            case "paid" : return payment.get("paid");
            case "outstanding" : return payment.get("outstanding");
            case "total" : return payment.get("total");
            default : return "";
        }
    }

    /**Add paid amount.*/
    public void pay(double amount){
        payment.pay(amount);
    }

    /**Refresh their payment status. */
    public void refresh_payment(){
        payment.refresh_payment(get_enrolment("id"));
    }

    /**Update payment.*/
    public void update_payment(String[] info){
        payment.update(info);
    }

    /**Method for searching.*/
    public boolean search(String word){
        boolean flag = false;
        ArrayList<String> sentence = new ArrayList<String>(Arrays.asList(name.split(" ")));
        sentence.addAll(new ArrayList<String>(Arrays.asList(address.split(" "))));
        flag = sentence.contains(word)|| id.equals(word) || ic.equals(word) || email.equals(word) || phone.equals(word) || level.equals(word);
        
        boolean result = flag || account.search(word) || enrolment.search(word) || payment.search(word);
        return result;
    }
}
