package Parser;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.FileWriter;
import java.util.HashMap;
import java.util.Iterator;

import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;

public class BusinessWithParking {
	public void method(String type1,String subType1,String subSubType1,String type2,String type3,String header,String jsonFilePath,String csvFilePath,String COMMA_DELIMITER,String NEW_LINE_SEPERATOR){
		//parse review.json and calculate the distribution of stars of useful review
		JSONParser parser = new JSONParser();
		FileWriter fileWriter = null;
		BufferedReader br=null;
		try{
			FileReader reader = new FileReader(jsonFilePath);
			br = new BufferedReader(reader);
			String line;
			line=br.readLine();
			HashMap<String,Double> business = new HashMap<>();
			HashMap<String,Double> npBusiness = new HashMap<>();
			HashMap<String,Double> parkingStar = new HashMap<>();
			HashMap<String,Double> noParkingStar = new HashMap<>();
			while(line!=null){
				Object obj = parser.parse(line);	
				JSONObject jsonObject = (JSONObject) obj;
				JSONObject attributes = (JSONObject) jsonObject.get(type1);
				if(attributes.containsKey(subType1)){
					JSONObject parking = (JSONObject) attributes.get(subType1);
					if(parking.containsKey(subSubType1)){
						Boolean parkingLot = (Boolean) parking.get(subSubType1);
						String state = (String) jsonObject.get(type2);
						Double stars = (Double) jsonObject.get(type3);
						if(!business.containsKey(state)){
							if(parkingLot){
								business.put(state, 1.0);
								npBusiness.put(state, 0.0);
								parkingStar.put(state, stars);
								noParkingStar.put(state, 0.0);
							}
							else {
								business.put(state, 0.0);
								npBusiness.put(state, 1.0);
								parkingStar.put(state, 0.0);
								noParkingStar.put(state, stars);
							}
						}else{
							if(parkingLot){
								double temp = business.get(state);
								business.put(state,temp+1);
								double temp2 = parkingStar.get(state);
								parkingStar.put(state, temp2+stars);
							}else{
								double temp = npBusiness.get(state);
								npBusiness.put(state,temp+1);
								double temp2 = noParkingStar.get(state);
								noParkingStar.put(state, temp2+stars);
							}

						}
					}
				}
				line = br.readLine();
			}
			//write starsArray into a csv file

			fileWriter = new FileWriter(csvFilePath);
			fileWriter.append(header);
			fileWriter.append(NEW_LINE_SEPERATOR);
			Iterator it1 = business.keySet().iterator();
			while(it1.hasNext()){
				String state =(String) it1.next();
				double bu = business.get(state);
				double npB = npBusiness.get(state);
				double pStar = parkingStar.get(state)/bu;
				double npStar = noParkingStar.get(state)/npB;
				fileWriter.append(state);
				fileWriter.append(COMMA_DELIMITER);
				fileWriter.append(String.valueOf(bu));
				fileWriter.append(COMMA_DELIMITER);
				fileWriter.append(String.valueOf(npB));
				fileWriter.append(COMMA_DELIMITER);
				fileWriter.append(String.valueOf(pStar));
				fileWriter.append(COMMA_DELIMITER);
				fileWriter.append(String.valueOf(npStar));
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
