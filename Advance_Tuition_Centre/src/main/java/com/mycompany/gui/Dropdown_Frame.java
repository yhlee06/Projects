package com.mycompany.gui;

import javax.swing.*;

public class Dropdown_Frame extends JPanel{
    public JComboBox<String> dropdown;
    public Dropdown_Frame(int width, int height, int x, int y, String[] options){
        setLayout(null);
        setBounds(x, y, width, height);
        dropdown = new JComboBox<>(options);
        dropdown.setBounds(0, 0, width, height);
        add(dropdown);
    }

    public String selection(){
        return dropdown.getSelectedItem().toString();
    }

    public void set_selection(int index){
        dropdown.setSelectedIndex(index);
    }
}
