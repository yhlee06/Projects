package com.mycompany.receptionist; /**Package containing the class.*/

/**Import API and classes needed*/
import java.time.YearMonth;
import java.time.temporal.ChronoUnit;
import com.mycompany.edit.*;

public class Student_Payment extends Details {
    /**Objects for reading and updating.*/
    Read_One file = new Read_One();
    Update update_file = new Update();

    /**Variables used.*/
    String id;
    double paid;
    double outstanding;
    double total;

    /**Constructor for student payment.*/
    public Student_Payment(String student_id){
        id = student_id;
        String[] payment = file.read(id, "Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt");
        /**Store payment details in double format.*/
        paid = Double.parseDouble(payment[1]);
        outstanding = Double.parseDouble(payment[2]);
        total = Double.parseDouble(payment[3]);
    }

    /**Method for refreshing payment.*/
    public void refresh_payment(String[] enrolments){
        double[] price = new double[enrolments.length];
        if (enrolments[0].isBlank()){
            return;
        }
        for (int index = 0; index < enrolments.length; index++){
            String class_id = file.read(enrolments[index], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt")[2];
            String[] class_data = file.read(class_id, "Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt");
            price[index] = Double.valueOf(class_data[4]);
        }

        double current_total = 0;
        for (int index = 0; index < enrolments.length; index++){
            String[] enrol_data = file.read(enrolments[index], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt");
            YearMonth start = YearMonth.parse(enrol_data[3]);
            YearMonth end;
            if (enrol_data[4].equals("null")){
                end = YearMonth.now();
            } else {
                end = YearMonth.parse(enrol_data[4]);
            }
            long difference = ChronoUnit.MONTHS.between(start, end) + 1;
            if (difference < 0){
                difference = 0;
            }
            current_total = current_total + ((double)difference * price[index]);
        }

        total = current_total;
        outstanding = total - paid;
        update_file.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt", id, 3, String.format("%.2f", current_total));
        update_file.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt", id, 2, String.format("%.2f", outstanding));
    }

    /**Method to get information about the payment.*/
    public String get(String info){
        switch (info){
            case "paid" : return String.format("%.2f", paid);
            case "outstanding" : return String.format("%.2f", outstanding);
            case "total" : return String.format("%.2f", total);
            default: return "";
        }
    }

    /**Method to pay.*/
    public void pay(double amount){
        paid = paid + amount;
        outstanding = total - paid;
        update_file.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt", id, 1, String.format("%.2f", paid));
        update_file.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt", id, 2, String.format("%.2f", outstanding));
    }

    /**Method to update the payments.*/
    public void update(String[] info){
        update_file.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt", id, 1, info[0]);
        update_file.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt", id, 2, info[1]);
        update_file.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt", id, 3, info[2]);
        paid = Double.valueOf(info[0]);
        outstanding = Double.valueOf(info[1]);
        total = Double.valueOf(info[2]);
    }

    /**Method for searching through payment information.*/
    public boolean search(String word){
        boolean flag = false;
        double amount;
        try{
            amount = Double.parseDouble(word);
            flag = (paid == amount) || (outstanding == amount) || (total == amount);
        } catch (Exception e){
            flag = false;
        }

        return flag;
    }
}
