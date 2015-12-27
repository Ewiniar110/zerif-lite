package Parser;

public class MainClass {
	public static void main(String[] args){
	//method  method(String type1,String subType1,String type2,String header,String jsonFilePath,String csvFilePath,String COMMA_DELIMITER,String NEW_LINE_SEPERATOR)
//	Category ys = new Category();
//	Parser_Json pj = new Parser_Json();
//	Review_Word_Analysis pwa = new Review_Word_Analysis();
	cat_loc_business clb = new cat_loc_business();
//	String type1 = "votes";
//	String subType1 = "funny";
//	String subSubType1 = "lot";
//	String type2 = "state";
//	String type2 ="stars";
//	String jsonFilePath = "C:/Python27/yelp_academic_dataset_business.json";
//	String csvFilePath = "C:/Python27/resturant_cate.csv";
//	String header="2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015";
//	String COMMA_DELIMITER = ",";
//	String NEW_LINE_SEPERATOR = "\n";
//	ys.method("votes", "funny", "stars", "stars,ratio,allStars,allRatio", "C:/VisDataSet/yelp_academic_dataset_business.json","C:/VisDataSet/business_12_14.csv" , ",","\n");
//	ys.method(type1,subType1,type2,header,jsonFilePath,csvFilePath,COMMA_DELIMITER,NEW_LINE_SEPERATOR);
//	pwa.method("text", "null", "C:/VisDataSet/yelp_academic_dataset_review.json", "null",",","\n");
//	cat_loc_business clb = new cat_loc_business();
	clb.method("categories","", "state", "", "C:/VisDataSet/yelp_academic_dataset_business.json","C:/VisDataSet/cat_loc_new.csv" , ",", "\n");
	String s1 = "ssss";
	String s2 =s1;
	s1 = "ss";
	System.out.println(s2);
	}
}