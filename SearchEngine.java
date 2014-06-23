public class RecipeSearch {
	HashMap<Integer, String> ingredients = new HashMap<Integer, String>();
	HashMap<Integer, Integer> recipeLengths = new HashMap<Integer, Integer>();
	HashMap<Integer, String> recipes = new HashMap<Integer, String>();
	HashMap<Integer, Integer> ingredientTally = new HashMap<Integer, Integer>();
	HashMap<Integer, String[]> ingredientUnits = new HashMap<Integer, String[]>();
	HashMap<Integer, double[]> ingredientAmounts = new HashMap<Integer, double[]>();
	HashMap<Integer, int[]> recipeIngredients = new HashMap<Integer, int[]>();
	HashMap<Integer, Double> ingWeights = new HashMap<Integer, Double>();
	HashMap<Integer, double[]> unitVectors = new HashMap<Integer, double[]>();
	HashMap<Integer, double[]> recipeDetails = new HashMap<Integer, double[]>();
	HashMap<Integer, String[]> recipeDetails2 = new HashMap<Integer, String[]>();
	HashMap<Integer, String> recipeDescriptions = new HashMap<Integer, String>();
	HashMap<Integer, String[]> recipeDirections = new HashMap<Integer, String[]>();
	HashMap<Integer, String[]> recipeReviews = new HashMap<Integer, String[]>();
	
	/*
	 * Read the files and create associated HashMaps
	 */
	public void readFile(String s) throws FileNotFoundException{
		Scanner scanner;
		File file;
		switch(s){
		case "ingredients":
			file = new File("ingredients.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				String[] parts = scanner.nextLine().split(":");
				ingredients.put(Integer.parseInt(parts[0]), parts[1]);
			}
			break;
		case "recipes":
			file = new File("recipes.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				String[] parts = scanner.nextLine().split(":");
				recipes.put(Integer.parseInt(parts[0]), parts[1]);
			}
			break;
		case "recipeLengths":
			file = new File("recipeLengths.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				String[] parts = scanner.nextLine().split(":");
				recipeLengths.put(Integer.parseInt(parts[0]), Integer.parseInt(parts[1]));
			}
			break;
		case "ingredientTally":
			file = new File("ingredientTally.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				String[] parts = scanner.nextLine().split(":");
				ingredientTally.put(Integer.parseInt(parts[0]), Integer.parseInt(parts[1]));
			}
			break;
		case "ingredientUnits":
			file = new File("ingredientUnits.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				/*String[] parts = scanner.next().split(":");
				int id = Integer.parseInt(parts[0]);
				int size = Integer.parseInt(parts[1]);
				String[] ingredients = new String[size];
				String[] parts2 = scanner.nextLine().split("@");
				for(int i = 0; i < size; i++)
					ingredients[i] = parts2[i];
				ingredientUnits.put(id, ingredients);*/
				String[] parts = scanner.nextLine().split(":");
                int id = Integer.parseInt(parts[0]);
                int size = Integer.parseInt(parts[1]);
                String[] ingredients = new String[size];
                String[] parts2 = parts[2].split("@");
                for(int i = 0; i < parts2.length; i++)
                    ingredients[i] = parts2[i];
                ingredientUnits.put(id, ingredients);
			}
			break;
		case "ingredientAmounts":
			file = new File("ingredientAmounts.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				String[] parts = scanner.next().split(":");
				int id = Integer.parseInt(parts[0]);
				int size = Integer.parseInt(parts[1]);
				double[] ingredients = new double[size];
/*				for(int i = 0; i < size; i++)
					ingredients[i] = scanner.nextDouble();
				ingredientAmounts.put(id, ingredients);*/
				String[] parts2 = parts[2].split("@");
                for(int i = 0; i < size; i++)
                    ingredients[i] = Double.parseDouble(parts2[i]);
                ingredientAmounts.put(id, ingredients);
				scanner.nextLine();			
			}
			break;
		case "recipeIngredients":
			file = new File("recipeIngredients.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				/*String[] parts = scanner.next().split(":");
				int id = Integer.parseInt(parts[0]);
				int size = Integer.parseInt(parts[1]);
				int[] ingredients = new int[size];
				for(int i = 0; i < size; i++)
					ingredients[i] = scanner.nextInt();
				recipeIngredients.put(id, ingredients);
				scanner.nextLine();*/
				String[] parts = scanner.nextLine().split(":");
                int id = Integer.parseInt(parts[0]);
                int size = Integer.parseInt(parts[1]);
                int[] ingredients = new int[size];
                String[] parts2 = parts[2].split("@");
                for(int i = 0; i < size; i++)
                    ingredients[i] = Integer.parseInt(parts2[i]);
                recipeIngredients.put(id, ingredients);
			}
			break;
		case "ingredientWeights":
			file = new File("ingredientWeights.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				String[] parts = scanner.nextLine().split(":");
				ingWeights.put(Integer.parseInt(parts[0]), Double.parseDouble(parts[1]));
			}
			break;
		case "unitVectors":
			file = new File("unitVectors.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				/*String[] parts = scanner.next().split(":");
				int id = Integer.parseInt(parts[0]);
				int size = Integer.parseInt(parts[1]);
				double[] units = new double[size];
				for(int i = 0; i < size; i++)
					units[i] = scanner.nextDouble();
				unitVectors.put(id, units);
				scanner.nextLine();*/
				String[] parts = scanner.nextLine().split(":");
                int id = Integer.parseInt(parts[0]);
                int size = Integer.parseInt(parts[1]);
                String[] parts2 = parts[2].split("@");
                double[] units = new double[size];
                for(int i = 0; i < size; i++)
                    units[i] = Double.parseDouble(parts2[i]);
                unitVectors.put(id, units);
			}
			break;
		case "recipeDetails":
			file = new File("recipeDetails.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				/*String[] parts = scanner.next().split(":");
				int id = Integer.parseInt(parts[0]);
				int size = Integer.parseInt(parts[1]);
				double[] details = new double[size];
				for(int i = 0; i < size; i++)
					details[i] = scanner.nextDouble();
				recipeDetails.put(id, details);
				scanner.nextLine();*/
				String[] parts = scanner.nextLine().split(":");
                int id = Integer.parseInt(parts[0]);
                int size = Integer.parseInt(parts[1]);
                double[] details = new double[size];
                String[] parts2 = parts[2].split("@");
                for(int i = 0; i < size; i++)
                    details[i] = Double.parseDouble(parts2[i]);
                recipeDetails.put(id, details);
			}
			break;
		case "recipeDetails2":
			file = new File("recipeDetails2.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				String[] parts = scanner.next().split(":");
				int id = Integer.parseInt(parts[0]);
				int size = Integer.parseInt(parts[1]);
				String[] details = new String[size];
				for(int i = 0; i < size; i++)
					details[i] = scanner.next();
				recipeDetails2.put(id, details);
				scanner.nextLine();
			}
			break;
		case "recipeDirections":
			file = new File("recipeDirections.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				/*int id = scanner.nextInt();
				String[] parts = scanner.nextLine().split("@");
				String[] directions = new String[parts.length-1];
				int j = 0;
				for(int i = 1; i < parts.length; i++, j++){
					directions[j] = parts[i];
				}

				recipeDirections.put(id, directions);*/
				String[] parts = scanner.nextLine().split("@");
              int id = Integer.parseInt(parts[0]);
              String[] parts2 = parts[1].split("::");
              String[] directions = new String[parts2.length-1];
              int j = 0;
              for(int i = 1; i < parts2.length; i++, j++){
                  directions[j] = parts2[i];
              }
              recipeDirections.put(id, directions);
			}
			break;
		case "recipeDescriptions":
			file = new File("recipeDescription.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				/*int id = scanner.nextInt();
				String desc = scanner.nextLine();
				recipeDescriptions.put(id, desc);*/
				/*String[] parts = scanner.nextLine().split("@");
              int id = Integer.parseInt(parts[0]);

              String desc = parts[1];
              recipeDescriptions.put(id, desc);*/
				String[] parts = scanner.nextLine().split("@");
//				System.out.println(parts[0]);
				int id = Integer.parseInt(parts[0]);
				
				String desc = parts[1];
				recipeDescriptions.put(id, desc);
			}
			break;
		case "recipeReviews":
			file = new File("recipeReviews.txt");
			scanner = new Scanner(file);
			while(scanner.hasNext()){
				String[] parts = scanner.nextLine().split(":");

				int id = Integer.parseInt(parts[0]);
				int size = Integer.parseInt(parts[1]);
				String[] reviews = new String[size];
				String[] parts2 = parts[2].split("@");
				for(int i = 0; i < parts2.length; i++){
				    reviews[i] = parts2[i];
				}
				recipeReviews.put(id, reviews);
			}
			break;
		}
	}
	
	/*
	 * Give id's to ingredients entered by the user.
	 */
	public int[] getInputIds(String[] input){
		int[] ids = new int[input.length];

		for(int i = 0; i < input.length; i++){
			for(int key : ingredients.keySet()){
				if(matchStrings(ingredients.get(key), input[i])){
					ids[i] = key;	

				}
			}
		}
		return ids;
	}
	
	/*
	 * The following 3 methods checks to see if ingredients entered match ingredients in the database.
	 * The method works as follows:
	 * 	1) Check for an exact match - if yes, this is the ingredient. If no, then:
	 * 	2) Check the length of each ingredient string.
	 * 	3) Loop through each ingredient--entered and in the database, and make a count of
	 * 		each letter that appears.
	 * 	4) If the difference between the 2 words is 0 or 1, return as a match. Otherwise, no match..
	 */
	
	public boolean matchStrings(String a, String b){
        String big, small;
        HashMap<Character, Integer> bigCount = new HashMap<Character, Integer>();
        HashMap<Character, Integer> smallCount = new HashMap<Character, Integer>();

        String parts[] = stringSize(a, b).split(":");
        big = parts[0].toLowerCase();
        big = big.replaceAll(" ", "");
        small = parts[1].toLowerCase();
        small = small.replaceAll(" ", "");
        if(big.equalsIgnoreCase(small)){
            return true;
        }
        else{
            bigCount = getLetterFrequency(big);
            smallCount = getLetterFrequency(small);
            int difference = 0;
            for(char letter : bigCount.keySet()){
                int smallTally, bigTally;
                bigTally = bigCount.get(letter);
                smallTally = smallCount.get(letter);
                if(bigTally >= smallTally)
                    difference += (bigTally - smallTally);
                else
                    difference += (smallTally - bigTally);
            }
            if(difference == 0){
                return true;
            }
            else{
                return false;
            }
        }
    }
	
	private String stringSize(String a, String b){
		if(a.length() >= b.length())
			return a + ":" + b;
		else{
			return b + ":" + a;
		}
	}
	
	private HashMap<Character, Integer> getLetterFrequency(String s){
		HashMap<Character, Integer> tally = new HashMap<Character, Integer>();
		
		char let = ' ';
		for(int i = 0; i < 32; i++)
			tally.put(let++, 0);
		let = 'a';
		for(int i = 0; i < 26; i++)
			tally.put(let++, 0);
		tally.put('®', 0);

		for(int i = 0; i < s.length(); i++){
			tally.put(s.charAt(i), tally.get(s.charAt(i)) + 1);
		}
