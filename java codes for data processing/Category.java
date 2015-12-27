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
import java.util.Map.Entry;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;

public class Category {
	public void method(String type1,String subType1,String type2,String header,String jsonFilePath,String csvFilePath,String COMMA_DELIMITER,String NEW_LINE_SEPERATOR){
		String[] catString = {"Chinese","Korean","Japanese","American","Mexican","Brazilian","Italian","Indian","French","Vietnamese","Turkish",
				"Greek","Spanish","Irish","Canadian","Thai","Persian","Argentine","Cambodian","Cuban","Australian"};
		HashSet<String> cate = new HashSet<String> (Arrays.asList(catString));
		HashSet<String> bbq = new HashSet<String>();
		bbq.add("BBQ");
		bbq.add("Barbeque");
		bbq.add("Grill");  bbq.add("Grills");
		bbq.add("Steakhouses");
		//all categories and counts
		HashSet<String> fastFood = new HashSet<String>();
		fastFood.add("Sandwiches");
		fastFood.add("Burgers");
		fastFood.add("Hotdogs");
		fastFood.add("Pizza");
		fastFood.add("Ice Cream");
		fastFood.add("Frozen Yogurt");
		fastFood.add("Fast Food");
		HashMap<String,HashMap<String,Integer>> stateCount = new HashMap<>();

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
				JSONArray category =  (JSONArray) jsonObject.get("categories");
				//state
				String state = (String) jsonObject.get("state");
				ArrayList<String> catList =  stringParser(category);
				if(!stateCount.containsKey(state)){
					HashMap<String,Integer> count = new HashMap<>();
					for(String s:catString)
						count.put(s, 0);
					count.put("BBQ", 0);
					count.put("Bakeries", 0);
					count.put("Fast Food", 0);
					count.put("Cafe", 0);
					count.put("Bakeries", 0);
					count.put("Pubs", 0);
					stateCount.put(state, count);
				}
				classifier(cate,fastFood,bbq,catList,stateCount,state);
				line = br.readLine();
			}
			//write starsArray into a csv file

			Iterator it = stateCount.entrySet().iterator();
			fileWriter = new FileWriter(csvFilePath);
			header = "state";
			for(int i =0;i<catString.length;i++){
				header = header+","+catString[i];
			}
			fileWriter.append(header);
			fileWriter.append(NEW_LINE_SEPERATOR);
			while(it.hasNext()){
				Map.Entry pair1 = (Map.Entry)it.next();
				HashMap<String,Integer> map2= (HashMap)pair1.getValue();
				Iterator it2 = map2.entrySet().iterator();
				int[] res = new int[catString.length];	
				while(it2.hasNext()){
					Map.Entry pair2 = (Map.Entry) it2.next();
					for(int i=0;i<catString.length;i++){
						if(catString[i].equals(pair2.getKey())){
							res[i] = (int)pair2.getValue();
							break;
						}
					}
				}
				fileWriter.append((String) pair1.getKey());
				for(int i = 0;i<catString.length;i++){
					fileWriter.append(COMMA_DELIMITER+res[i]);
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
		Iterator it = stateCount.entrySet().iterator();
		while(it.hasNext()){
			Map.Entry pair = (Map.Entry)it.next(); 
			System.out.println(pair.getKey()+"  "+ pair.getValue());
		}
	}
	public ArrayList<String> stringParser(JSONArray string){
		int index = 0;
		ArrayList<String> catList = new ArrayList<>();
		int pos = 0;

		while(index < string.size()){
			pos =0;
			String s;
			s = (String) string.get(index);

			for(int i = 0 ;i < s.length(); i++){
				char c = s.charAt(i);
				if(!((c>='a' && c<='z')||(c>='A'&&  c<='Z'))){
					if(pos>=0 && i - pos >1){
						catList.add(s.substring(pos, i));
						pos = i+1;
					}else if(pos>=0 && i - pos ==1){
						pos = i+1;
					}
				}
			}
			catList.add(s.substring(pos,s.length()));
			index++;
		}
		return catList;
	}
	public void classifier(HashSet<String> catCountry,HashSet<String> fastFood,HashSet<String> bbq,ArrayList<String> strings,HashMap<String,HashMap<String,Integer>> stateCount
			,String state){
		for(int i = 0; i < strings.size();i++){
			if(catCountry.contains(strings.get(i)) && (strings.contains("Restaurants")||strings.contains("Food")||strings.contains("Cuisine"))){
				int num = stateCount.get(state).get(strings.get(i));
				HashMap<String,Integer> temp = stateCount.get(state);
				temp.put(strings.get(i),num+1);
				stateCount.put(state,temp);
			}
		}
	}
}

