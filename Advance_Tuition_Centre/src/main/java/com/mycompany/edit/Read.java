package com.mycompany.edit;

import java.io.*;
import java.util.ArrayList;

public class Read {
    public String[] read(String file_path){
        ArrayList<String> data = new ArrayList<String>();
        String line;
        
        try{
            FileReader read_file = new FileReader(file_path);
            BufferedReader read_buffer = new BufferedReader(read_file);
            while ((line = read_buffer.readLine()) != null){
                data.add(line);
            }

            read_buffer.close();
        }
        catch (IOException e){
            System.out.println("Error has occurred. " + e.getStackTrace());
            
        }

        return data.toArray(new String[0]);
    }
}