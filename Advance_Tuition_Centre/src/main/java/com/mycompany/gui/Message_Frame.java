package com.mycompany.gui;

import javax.swing.*;

public class Message_Frame {
    public static void message_frame(String title, String message){
        JOptionPane.showMessageDialog(null, message, title, JOptionPane.INFORMATION_MESSAGE);
    }

    public static String input_frame(String title, String message){
        String selected = JOptionPane.showInputDialog(null, message, title, JOptionPane.PLAIN_MESSAGE);
        return selected;
    }

    public static boolean confirm_frame(String title, String message){
        int option = JOptionPane.showConfirmDialog(null, message, title, JOptionPane.YES_NO_OPTION);
        if (option == JOptionPane.YES_OPTION){
            return true;
        }else{
            return false;
        }
    }
}
