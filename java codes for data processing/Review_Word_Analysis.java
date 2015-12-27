package Parser;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.FileWriter;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Iterator;
import java.util.Map;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;

public class Review_Word_Analysis {
	public void method(String type1,String header,String jsonFilePath,String csvFilePath,String COMMA_DELIMITER,String NEW_LINE_SEPERATOR){
		HashMap<String,Integer> useful = new HashMap();
		HashMap<String,Integer> funny = new HashMap();
		HashMap<String,Integer> cool = new HashMap();
		ArrayList<HashMap> mapList = new ArrayList<>();
		//parse review.json and calculate the distribution of stars of useful review
		JSONParser parser = new JSONParser();
		FileWriter fileWriter = null;
		BufferedReader br=null;
		try{
			FileReader reader = new FileReader(jsonFilePath);
			br = new BufferedReader(reader);
			String line;
			line=br.readLine();
			while(line!=null){
				Object obj = parser.parse(line);
				JSONObject jsonObject = (JSONObject) obj;
				//category
				String review =  (String) jsonObject.get("text");
				//state
				JSONObject votes = (JSONObject) jsonObject.get("votes");
				long usefulNum= (long) votes.get("useful");
				long coolNum = (long) votes.get("cool");
				long funnyNum = (long) votes.get("funny");
				ArrayList<String> reviewList =  stringParser(review);
				if(usefulNum > 0) classifier(reviewList,useful);
				if(coolNum>0) classifier(reviewList,cool);
				if(funnyNum>0) classifier(reviewList,funny);
				line = br.readLine();
			}
			//write starsArray into a csv file

			String[] csvPath = {"C:/VisDataSet/useful_review_text.csv","C:/VisDataSet/funny_review_text.csv","C:/VisDataSet/cool_review_text.csv"};
			mapList.add(useful); mapList.add(funny); mapList.add(cool);
			for(int i=0;i<3;i++){
				Iterator it = mapList.get(i).entrySet().iterator();
				fileWriter = new FileWriter(csvPath[i]);

				while(it.hasNext()){
					Map.Entry pair1 = (Map.Entry)it.next();
					fileWriter.append((String) pair1.getKey());
					fileWriter.append(COMMA_DELIMITER);
					fileWriter.append(String.valueOf(pair1.getValue()));
					fileWriter.append(NEW_LINE_SEPERATOR);
				}
			}

		}catch(Exception e){
			e.printStackTrace();
		}finally{
			try{
				fileWriter.flush();
				fileWriter.close();
				br.close();
			}catch(Exception e){
				e.printStackTrace();
			}
		}
	}
	public ArrayList<String> stringParser(String string){
		//index range of a word in string
		int start = 0;
		int end = 0;
		ArrayList<String> catList = new ArrayList<>();
		if(string == null || string.length()==0)
			return catList;
		//move to the first letter in string
		while(start<string.length()&&!((string.charAt(start)>='a' && string.charAt(start)<='z') ||(string.charAt(start)>='A' &&string.charAt(start)<='Z'))){
			start++;
			end++;
		}
		while(end < string.length()){
			char c = string.charAt(end);
			if(!((c>='a' && c<='z')||(c>='A'&&  c<='Z')||c=='\'')){
				if(end > start){
					catList.add(string.substring(start, end));
					start =end +1;
					//move to the next letter
					while(start<string.length()&&!((string.charAt(start)>='a' && string.charAt(start)<='z') ||(string.charAt(start)>='A' &&string.charAt(start)<='Z'))){
						start++;
					}
					end = start;
				}
			}
			end++;
		}
		return catList;
	}
	public void classifier(ArrayList<String> stringList,HashMap<String,Integer> map){
		for(int i =0;i<stringList.size();i++){
			String temp = stringList.get(i);
			if(!map.containsKey(temp)) map.put(temp, 0);
			else{
				int t = map.get(temp);
				map.put(temp, t+1);
			}
		}
	}
}
