package Parser;

public class MainClass {
	public static void main(String[] args){
	//method  method(String type1,String subType1,String type2,String header,String jsonFilePath,String csvFilePath,String COMMA_DELIMITER,String NEW_LINE_SEPERATOR)
	YelpingSince ys = new YelpingSince();
	String type1 = "yelping_since";
	String subType1 = "Parking";
	String subSubType1 = "lot";
	String type2 = "state";
	String type3 ="stars";
	String jsonFilePath = "C:/Python27/yelp_academic_dataset_user.json";
	String csvFilePath = "C:/Python27/yelping_since_monthly.csv";
	String header="2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015";
	String COMMA_DELIMITER = ",";
	String NEW_LINE_SEPERATOR = "\n";
//	pj.method("votes", "useful", "stars", "stars,ratio,allStars,allRatio", "C:/Python27/yelp_academic_dataset_review.json","C:/Python27/useful_review_stars1.csv" , ",","\n");
	//ys.method(type1,subType1,type2,header,jsonFilePath,csvFilePath,COMMA_DELIMITER,NEW_LINE_SEPERATOR);
	String s1 = "ssss";
	String s2 =s1;
	s1 = "ss";
	System.out.println(s2);
	}
}