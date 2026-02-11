package com.mycompany.edit;

import java.io.*;

public class Add {
    public void add_to_file(String file_path, String data){
        try{
            FileWriter write_file = new FileWriter(file_path, true);
            BufferedWriter write_buffer = new BufferedWriter(write_file);
            write_buffer.write(data);
            write_buffer.newLine();
            write_buffer.close();
        }
        catch (IOException e){
            System.out.println("An error has occurred." + e);
        }
    }
}
