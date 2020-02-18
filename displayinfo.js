let dictionary = {'effective_time': '20181115', 'inactive_ingredient': ['Inactive Ingredients: Acesulfame Potassium, Citric acid, filtered water, malic acid, natural flavors, potassium citrate, sodium benzoate, sucralose'], 'keep_out_of_reach_of_children': ['Keep out of reach of children'], 'purpose': ['Purpose-Nasal Decongestant'], 'warnings': ["Do not exceed recommended dosage. If dizziness or sleeplessness occur, symptoms do not improve or are accompanied by a fever-consult a doctor. Do not exceed recommended dosage. If dizziness or sleeplessness occur, consult a doctor. Drug interaction Precaution: Do not use this product if you are now taking a prescription monoamine oxidase inhibitor (MAOI) (Certain drugs for depression, psychiatric, or emotional conditions, or Parkinson's disease), or for 2 weeks after stopping the MAOI drug. If you are uncertain whether your prescription drug contains an MAOI, consult a health professional before using this product."], 'spl_product_data_elements': ['Ephed 60 Pseudoephedrine PSEUDOEPHEDRINE HYDROCHLORIDE PSEUDOEPHEDRINE WATER SODIUM BENZOATE POTASSIUM SORBATE SUCRALOSE MALIC ACID CITRIC ACID MONOHYDRATE LIQUID BERRY FLAVOR BERRY RED'], 'openfda': {}, 'version': '2', 'dosage_and_administration': ['Adults and children 12 years of age and older: 1 bottle every 4-6 hours. Do not exceed 4 bottles in 24 hours. Children under 12: Do not use'], 'pregnancy_or_breast_feeding': ['if pregnant or breast feeding a baby, consult a health professional before use.'], 'package_label_principal_display_panel': ['ephed 60'], 'indications_and_usage': ['For the temporary relief of nasal decongestiom due to the commomn cold, hay fever or other upper respiratpry allergies. Temporarily relieves nasal stuffiness. Decongests nasal passages: shrinks swollen membranes. Temporarily restores freer breathing through the nose. Helps decongest sinus openings and passages; temporarily relieves sinus congestion and pressure. Promotes nasal and/or sinus drainage. temporarily relieves sinus congestion and pressure.'], 'set_id': '00023ca2-4433-4f88-8252-bc8c1d7ea2e0', 'id': '66a9f5f2-42e8-4500-a1f4-d4eb4e86758b', 'active_ingredient': ['Active Ingredient (In Each Bottle)---Pseudoephedrine HCl 60 mg']}

keywords = ["label", "purpose", "usage", "dosage",
            "activeIngredients", "inactiveIngredients",
            "effectiveTime", "warnings", "pregnancy"]

// Collects interesting drug information from dictionary.
data = [dictionary.package_label_principal_display_panel[0],
        dictionary.purpose[0],
        dictionary.indications_and_usage[0],
        dictionary.dosage_and_administration[0],
        dictionary.active_ingredient[0],
        dictionary.inactive_ingredient[0],
        dictionary.effective_time,
        dictionary.warnings[0],
        dictionary.pregnancy_or_breast_feeding[0]]

// Displays information in HTML table.
for(let i = 0; i < data.length; i++) {
  document.getElementById(keywords[i]).innerHTML = data[i]
}
