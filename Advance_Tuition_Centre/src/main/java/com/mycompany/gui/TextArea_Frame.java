package com.mycompany.gui;

import javax.swing.*;

public class TextArea_Frame extends JPanel{
	public JTextArea text_area;
	
	public TextArea_Frame(int width, int height, int x, int y) {
		setLayout(null);
		setBounds(x, y, width, height);
		
		text_area = new JTextArea();
		text_area.setLineWrap(true);
		text_area.setWrapStyleWord(true);
		
		JScrollPane scroll = new JScrollPane(text_area);
		scroll.setBounds(0, 0, width, height);
		
		add(scroll);
	}
	
	public String get_input() {
		return text_area.getText();
	}
	
	public void set_text(String text) {
		text_area.setText(text);
	}
	

}
