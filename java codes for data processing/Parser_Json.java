package Parser;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.FileWriter;

import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;

public class Parser_Json {
	public void method(String type1,String subType1,String type2,String header,String jsonFilePath,String csvFilePath,String COMMA_DELIMITER,String NEW_LINE_SEPERATOR){
		//parse review.json and calculate the distribution of stars of useful review
		JSONParser parser = new JSONParser();
		FileWriter fileWriter = null;
		BufferedReader br=null;
		try{
			FileReader reader = new FileReader(jsonFilePath);
			br = new BufferedReader(reader);
			String line;
			line=br.readLine();
			double cnts = 0;
			int[] starsArray = new int[5];
			double allCnts = 0;
			int[] allStarsArray = new int[5];
			while(line!=null){
				Object obj = parser.parse(line);	
				JSONObject jsonObject = (JSONObject) obj;
				JSONObject voteUsefulJson = (JSONObject) jsonObject.get(type1);
				long usefulCnts = (long) voteUsefulJson.get(subType1);
				long usefulStars = (long) jsonObject.get(type2);
				allCnts++;
				allStarsArray[(int)usefulStars-1]++;
				if(usefulCnts > 0){
					cnts++;
					starsArray[(int)usefulStars-1]++;
				}
				line = br.readLine();
			}
			//write starsArray into a csv file
			
			fileWriter = new FileWriter(csvFilePath);
			fileWriter.append(header);
			fileWriter.append(NEW_LINE_SEPERATOR);
			for(int i=0;i<5;i++){
				//useful review stars calculation
				fileWriter.append(String.valueOf(starsArray[i]));
				fileWriter.append(COMMA_DELIMITER);
				double ratio= starsArray[i]/cnts;
				fileWriter.append(String.valueOf(ratio));
				fileWriter.append(COMMA_DELIMITER);
				//all review stars calculation
				fileWriter.append(String.valueOf(allStarsArray[i]));
				fileWriter.append(COMMA_DELIMITER);
				double allRatio = allStarsArray[i]/allCnts;
				fileWriter.append(String.valueOf(allRatio));
				fileWriter.append(NEW_LINE_SEPERATOR);
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
}