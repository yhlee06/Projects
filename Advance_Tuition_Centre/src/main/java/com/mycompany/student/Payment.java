package com.mycompany.student;

public class Payment {
    private String user_id;
    private String paid;
    private String outstanding;
    private String payable;
    
    public Payment(String user_id, String paid, String outstanding, String payable){
        this.user_id = user_id;
        this.paid = paid;
        this.outstanding = outstanding;
        this.payable = payable;
    }

    public String get(String info){
        switch (info) {
            case "id": return user_id;
            case "paid": return paid;
            case "outstanding": return outstanding;
            case "payable": return payable;
            default: return "";
        }
    }
    
}
