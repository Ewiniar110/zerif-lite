package Parser;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.FileWriter;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Iterator;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;

public class cat_loc_business {

	HashSet<String> res = new HashSet<>();

	public void method(String type1, String subType1, String type2,
			String header, String jsonFilePath, String csvFilePath,
			String COMMA_DELIMITER, String NEW_LINE_SEPERATOR) {
		// key words to make a business a restaurant
		String[] restaurant = { "Resturants", "Cafes", "Bars", "Pubs", "Food",
				"Breakfast", "Brunch", "Lunch", "Dinner", "Supper", "Bakeries" };
		// key words of featured restaurant
		String[] catString = { "Chinese", "Korean", "Japanese", "American",
				"Mexican", "Brazilian", "Italian", "Indian", "French",
				"Vietnamese", "Turkish", "Greek", "Spanish", "Irish",
				"Canadian", "Thai", "Persian", "Argentine", "Cambodian",
				"Cuban", "Australian" };
		// convert the array to hashset
		HashSet<String> cate_country = new HashSet<String>(
				Arrays.asList(catString));
		res = new HashSet<String>(Arrays.asList(restaurant));
		HashSet<String> cities = new HashSet<String>();

		JSONParser parser = new JSONParser();
		FileWriter fileWriter = null;
		BufferedReader br = null;
		try {
			// read the data from a csv file
			FileReader reader = new FileReader(jsonFilePath);
			br = new BufferedReader(reader);
			String line;
			line = br.readLine();
			ArrayList<String> name_res = new ArrayList<>();
			ArrayList<String> lat = new ArrayList<>();
			ArrayList<String> lng = new ArrayList<>();
			ArrayList<String> city_res = new ArrayList<>();
			ArrayList<String> zip_code = new ArrayList<>();
			ArrayList<String> cate = new ArrayList<>();

			while (line != null) {
				//parse each line and return a json object
				Object obj = parser.parse(line);
				JSONObject jsonObject = (JSONObject) obj;
				// category
				JSONArray category = (JSONArray) jsonObject.get("categories");
				// state
				// determine if this business is a restaurant according to the category
				boolean isRes = isRestaurant(category);
				//since there are some acronyms of states that are longer than 2 and can not be recognized, 
				//we only analyze those consisting of 2 chars
				String state_temp = (String) jsonObject.get("state");
				if (isRes && state_temp.length() == 2) {
					cities.add((String) jsonObject.get("city"));
					String address = (String) jsonObject.get("full_address");
					String zipcode = zipCode(address);
					zip_code.add(zipcode);
					String name_temp = (String) (jsonObject.get("name"));
					//replace the comma with space in the name 
					for (int i = 0; i < name_temp.length(); i++) {
						name_temp = name_temp.replace(',', ' ');
					}
					name_res.add(name_temp);
					lat.add(String.valueOf(jsonObject.get("latitude")));
					lng.add(String.valueOf(jsonObject.get("longitude")));
					city_res.add((String) jsonObject.get("city"));
					//parse the complex category string in the original data
					ArrayList<String> category_word = stringParser(category);
					//determine the country of the served food
					cate.add(classifier(cate_country, category_word));

				}
				line = br.readLine();
			}
			// write starsArray into a csv file
			fileWriter = new FileWriter(csvFilePath);
			header = "state,zip_code,name,latitude,longitude,category";
			fileWriter.append(header);
			fileWriter.append(NEW_LINE_SEPERATOR);
			for (int i = 0; i < name_res.size(); i++) {
				fileWriter.append(city_res.get(i));
				fileWriter.append(',');
				fileWriter.append(zip_code.get(i));
				fileWriter.append(',');
				fileWriter.append(name_res.get(i));
				fileWriter.append(',');
				fileWriter.append(lat.get(i));
				fileWriter.append(',');
				fileWriter.append(lng.get(i));
				fileWriter.append(',');
				fileWriter.append(cate.get(i));
				fileWriter.append('\n');
			}
		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			try {
				fileWriter.flush();
				fileWriter.close();
				br.close();
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
	}

	// to determine if it is a restaurant
	public boolean isRestaurant(JSONArray string) {
		int index = 0;
		ArrayList<String> catList = new ArrayList<>();
		int pos = 0;

		while (index < string.size()) {
			pos = 0;
			String s;
			s = (String) string.get(index);

			for (int i = 0; i < s.length(); i++) {
				char c = s.charAt(i);
				if (!((c >= 'a' && c <= 'z') || (c >= 'A' && c <= 'Z'))) {
					if (pos >= 0 && i - pos > 1) {
						catList.add(s.substring(pos, i));
						pos = i + 1;
					} else if (pos >= 0 && i - pos == 1) {
						pos = i + 1;
					}
				}
			}
			if (res.contains(s.substring(pos, s.length()))) {
				return true;
			}
			catList.add(s.substring(pos, s.length()));
			index++;
		}
		return false;
	}
	
	//to get the zip code in the address string
	public String zipCode(String full_address) {
		for (int i = full_address.length() - 1; i >= 0; i--) {
			if (full_address.charAt(i) == ',') {
				for (int j = i + 2; j < full_address.length(); j++) {
					if (full_address.charAt(j) == ' ') {
						return full_address.substring(j + 1,
								full_address.length());
					}
				}
			}
		}
		return null;
	}

	//parse the string of category and return a list of strings about category
	public ArrayList<String> stringParser(JSONArray string) {
		int index = 0;
		ArrayList<String> catList = new ArrayList<>();
		int pos = 0;

		while (index < string.size()) {
			pos = 0;
			String s;
			s = (String) string.get(index);

			for (int i = 0; i < s.length(); i++) {
				char c = s.charAt(i);
				if (!((c >= 'a' && c <= 'z') || (c >= 'A' && c <= 'Z'))) {
					if (pos >= 0 && i - pos > 1) {
						catList.add(s.substring(pos, i));
						pos = i + 1;
					} else if (pos >= 0 && i - pos == 1) {
						pos = i + 1;
					}
				}
			}
			catList.add(s.substring(pos, s.length()));
			index++;
		}
		return catList;
	}

	// to determine the feature of this restaurant, aka, the country of the
	public String classifier(HashSet<String> catCountry,
			ArrayList<String> strings) {
		for (int i = 0; i < strings.size(); i++) {
			if (catCountry.contains(strings.get(i))
					&& (strings.contains("Restaurants")
							|| strings.contains("Food") || strings
								.contains("Cuisine"))) {
				return strings.get(i);
			}
		}
		return "";
	}
}
