package Parser;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.FileWriter;
import java.util.HashMap;

import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;

public class YelpingSince {
	public void method(String type1,String subType1,String type2,String header,String jsonFilePath,String csvFilePath,String COMMA_DELIMITER,String NEW_LINE_SEPERATOR){
		//parse review.json and calculate the distribution of stars of useful review
		JSONParser parser = new JSONParser();
		FileWriter fileWriter = null;
		BufferedReader br=null;
		try{
			FileReader reader = new FileReader(jsonFilePath);;
			br = new BufferedReader(reader);
			String line;
			line=br.readLine();
			int[] yearlyIncrease = new int[12];
			int[][] monthlyIncrease = new int[12][12];
			while(line!=null){
				Object obj = parser.parse(line);
				JSONObject jsonObject = (JSONObject) obj;
				String yelpingYearStr = (String) jsonObject.get(type1);
				int len = yelpingYearStr.length();
				String yelpingMonthStr = yelpingYearStr.substring(len-2, len);
				yelpingYearStr = yelpingYearStr.substring(0, 4);
				int yelpingYear=Integer.parseInt(yelpingYearStr),yelpingMonth=Integer.parseInt(yelpingMonthStr);
				yearlyIncrease[yelpingYear-2004]++;
				monthlyIncrease[yelpingYear-2004][yelpingMonth-1]++;
				line = br.readLine();
			}
			//write starsArray into a csv file

			fileWriter = new FileWriter(csvFilePath);
			fileWriter.append(header);
			fileWriter.append(NEW_LINE_SEPERATOR);
			
			for(int i =0;i<12;i++){
				for(int j =0;j<12;j++){
				fileWriter.append(String.valueOf(monthlyIncrease[j][i]));
				fileWriter.append(COMMA_DELIMITER);
				}
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
