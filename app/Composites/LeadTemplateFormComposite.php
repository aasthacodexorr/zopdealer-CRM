<?php

namespace App\Composites;

use App\Builder\FormElementBuilder;
use App\Entities\LeadTemplateForm;
use App\Entities\LeadTemplateFormElement;
use App\Interfaces\FormInterface;
use App\Models\LeadTemplateFormElementModel;
use App\Models\LeadTemplateFormModel;
use App\FormElements\TextFormElement as TextElement;
use App\FormElements\NumberFormElement as NumberElement;
use App\FormElements\ColorFormElement as ColorElement;
use App\FormElements\DateFormElement as DateElement;
use App\FormElements\EmailFormElement as EmailElement;
use App\FormElements\SelectFormElement as SelectElement;
use App\FormElements\TelFormElement as TelElement;
use App\FormElements\TimeFormElement as TimeElement;
use App\FormElements\TextAreaFormElement as TextAreaElement;
use App\FormElements\UrlFormElement as UrlElement;
use App\FormElements\CheckboxFormElement as CheckboxElement;
use App\FormElements\HeadingLevelFourFormElement as Heading4;
use App\FormElements\HeadingLevelTwoFormElement as Heading2;
use App\FormElements\ParagraphFormElement as ParagraphElement;
use App\FormElements\RadioFormElement as RadioElement;
use App\FormElements\SectionFormElement as SectionElement;
use App\FormElements\FileFormElement as FileElement;





class LeadTemplateFormComposite implements FormInterface {
    private $name;
    private $description;
    private $type;
    private $id;
    private $slug;
    private $recipient_email;
    private $recipient_phone;
    private $recipient_adf_email;
    private $source;
    private $disclosure;
    private $default_terms_and_condition;
    private $override_custom_template;
    private $custom_template;
    private $VOI;
    private $custom_css;
    private $custom_less;
    private $auto_reply;
    private $auto_reply_template;
    private $admin_reply_template;
    private $override_auto_reply_content;
    private $auto_reply_content;


    private $form_elements = [];

    public function getAllForms($items = 10, $paginate = true ) : array {
        $leadTemplateFormModel = new LeadTemplateFormModel();
        if($paginate){
            $data = [
                'lead_templates' => $leadTemplateFormModel->orderBy('id', 'DESC')->asObject()->paginate($items),
                'pager' => $leadTemplateFormModel->orderBy('id', 'DESC')->pager->getDetails(),
            ];
        } else {
            $data = [
                'lead_templates' => $leadTemplateFormModel->orderBy('id', 'DESC')->asObject()->findAll(),
            ];
        }

        return $data;
    }

    
    public function getElements($id) : array {
        $leadTemplateFormModel = new LeadTemplateFormModel();
        $lead_form = $leadTemplateFormModel->asArray()->find($id);

        $this->name = $lead_form['name'];
        $this->description = $lead_form['description'];
        $this->type = $lead_form['type'];
        $this->id = $lead_form['type'];
        $this->slug = $lead_form['slug'];
        $this->recipient_email = $lead_form['recipient_email'];
        $this->recipient_phone = $lead_form['recipient_phone'];
        $this->recipient_adf_email = $lead_form['recipient_adf_email'];
        $this->source = $lead_form['source'];
        $this->disclosure = $lead_form['disclosure'];
        $this->VOI = $lead_form['VOI'];
        $this->default_terms_and_condition = $lead_form['default_terms_and_condition'];
        $this->override_custom_template = $lead_form['override_custom_template'];
        $this->custom_template = $lead_form['custom_template'];
        $this->custom_css = $lead_form['custom_css'];
        $this->custom_less = $lead_form['custom_less'];
        $this->auto_reply = $lead_form['auto_reply'];
        $this->auto_reply_template = $lead_form['auto_reply_template'];
        $this->admin_reply_template = $lead_form['admin_reply_template'];
        $this->override_auto_reply_content = $lead_form['override_auto_reply_content'];
        $this->auto_reply_content = $lead_form['auto_reply_content'];

        $leadTemplateFormElementModel = new LeadTemplateFormElementModel();
        $lead_form['dom_elements'] = $leadTemplateFormElementModel->where('lead_template_form_id',$id)->findAll();

        $this->form_elements = $lead_form['dom_elements'];


        return $lead_form;
    }

    public function saveElements($data , $id = null) : array {
       // var_dump($data);
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->type = $data['type'];
        $this->slug = $data['slug'];
        $this->source = $data['source'];
        $this->recipient_email = $data['recipient_email'];
        $this->recipient_phone = $data['recipient_phone'];
        $this->recipient_adf_email = $data['recipient_adf_email'];
        $this->disclosure = $data['disclosure'];
        $this->default_terms_and_condition = $data['default_terms_and_condition'];
        $this->VOI = $data['VOI'];
        $this->override_custom_template = $data['override_custom_template'];
        $this->custom_template = $data['custom_template'];
        $this->custom_css = $data['custom_css'];
        $this->custom_less = $data['custom_less'];
        $this->auto_reply = $data['auto_reply'];
        $this->auto_reply_template = $data['auto_reply_template'];
        $this->admin_reply_template = $data['admin_reply_template'];
        $this->override_auto_reply_content = $data['override_auto_reply_content'];
        $this->auto_reply_content = $data['auto_reply_content'];

        unset($data['name']);
        unset($data['description']);
        unset($data['type']);
        unset($data['slug']);
        unset($data['recipient_email']);
        unset($data['recipient_phone']);
        unset($data['recipient_adf_email']);
        unset($data['source']);
        unset($data['disclosure']);
        unset($data['default_terms_and_condition']);
        unset($data['VOI']);
        unset($data['override_custom_template']);
        unset($data['custom_template']);
        unset($data['custom_css']);
        unset($data['custom_less']);
        unset($data['auto_reply']);
        unset($data['auto_reply_template']);
        unset($data['admin_reply_template']);
        unset($data['override_auto_reply_content']);
        unset($data['auto_reply_content']);

        $entity_data = [
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'slug' => $this->slug,
            'recipient_email' => $this->recipient_email,
            'recipient_phone' => $this->recipient_phone,
            'recipient_adf_email' => $this->recipient_adf_email,
            'source' => $this->source,
            'disclosure' => $this->disclosure,
            'default_terms_and_condition' => $this->default_terms_and_condition,
            'VOI' => $this->VOI,
            'override_custom_template' => $this->override_custom_template,
            'custom_template' => $this->custom_template,
            'custom_css' => $this->custom_css,
            'custom_less' => $this->custom_less,
            'auto_reply' => $this->auto_reply,
            'auto_reply_template' => $this->auto_reply_template,
            'admin_reply_template' => $this->admin_reply_template,
            'override_auto_reply_content' => $this->override_auto_reply_content,
            'auto_reply_content' => $this->auto_reply_content
            ];
        if($id != null){
            $this->id = $id;
            $entity_data['id'] = $id; 
        }
        $leadTemplateEntity = new LeadTemplateForm($entity_data);
        $leadTemplateFormModel = new LeadTemplateFormModel();

        for ($i=0; $i < count($data['dom_name']); $i++) { 
           $each_form_element = [];
           foreach ($data as $key => $value) {
               $each_form_element[$key] = $data[$key][$i];
           }

           array_push($this->form_elements, $each_form_element);
        }

        $form_elements_validation_flag = [];
        $form_elements_validation_list = [];
        foreach ($this->form_elements as $form_element) {
            //var_dump($form_element);
            $leadTemplateFormElementModelValidator = new LeadTemplateFormElementModel();
            array_push($form_elements_validation_flag, $leadTemplateFormElementModelValidator->cleanRules()->validate($form_element));
            array_push($form_elements_validation_list, $leadTemplateFormElementModelValidator->cleanRules()->errors());
        }

        // If any of the form elements have error than return
        if(in_array(false,$form_elements_validation_flag)){
            return  ['errors' => ['dom_elements' => $form_elements_validation_list]];
        }

        if($leadTemplateFormModel->save($leadTemplateEntity) === false){
                    return ['errors' => $leadTemplateFormModel->errors()];
        } else {

        if($id != null){
            (new LeadTemplateFormElementModel())->where('lead_template_form_id',$id)->delete(true);
        }

        $form_elements_validation_flag = [];
        $form_elements_validation_list = [];
        foreach ($this->form_elements as $form_element) {
            //var_dump($form_element);
            $form_id = $id == null ? $leadTemplateFormModel->insertID : $id;
            $form_element['lead_template_form_id'] = $form_id;
            $leadTemplateFormElementEntity = new LeadTemplateFormElement($form_element);
            $leadTemplateFormElementModel = new LeadTemplateFormElementModel();

            if($leadTemplateFormElementModel->save($leadTemplateFormElementEntity) === false){
                array_push($form_elements_validation_list, $leadTemplateFormElementModel->errors());
             } else {
                array_push($form_elements_validation_list, []);
             }
        }

        /** This condition may be required in future if any database validations are added such as is_unqiue */
        foreach($form_elements_validation_list as $each_validation){
            if($each_validation == []){

            } else {
                return  [
                    'success' => 'Main form has been saved but some form elements had error. Please resolve the errors.',
                    'errors' => ['dom_elements' => $form_elements_validation_list],
                    'lead_template_id' => $form_id
                ]; 
            }
        }
        

        }
        
        return [
            'success' => 'Lead Template Form has been saved.',
            'lead_template_id' => $leadTemplateFormModel->insertID,
        ];
    }

    public function validateElements() : array {
        $rules = [];
        foreach ($this->form_elements as $form_element) {
            if(strlen($form_element->dom_validation) > 0){

                  $rules[$form_element->dom_name]    = [
                    'label' => $form_element->dom_label, 
                    'rules' => $form_element->dom_validation];
            }
        }
        return $rules;
    }
    
    /**
     * renderElements This renders the elements of the form.
     *
     * @return void
     */
    public function renderElements() : String {
        $form_element_object = null;
        $template_string = '';
        foreach ($this->form_elements as $form_element) {

            $form_element_object = $this->elementObject($form_element->dom_type);
            $form_element_object->initialize($form_element->dom_name,$form_element->dom_value,$form_element->dom_type);
            if(trim($form_element->dom_placeholder) != ''){
                $form_element_object->setPlaceholder($form_element->dom_placeholder);
            }
            if(trim($form_element->dom_label) != ''){
                $form_element_object->setLabel($form_element->dom_label);
            }
            if(trim($form_element->dom_id) != ''){
                $form_element_object->setId($form_element->dom_id);
            }
            if(trim($form_element->dom_class) != ''){
                $form_element_object->setClass($form_element->dom_class);
            }
            if(trim($form_element->dom_validation) != ''){
                $form_element_object->setValidation($form_element->dom_validation);
            }
            if(trim($form_element->options) != ''){
                $form_element_object->setOpitons($form_element->options);
            }
            $template_string .= $this->mapElement($form_element_object);
        }

        return $template_string;
    }

    protected function mapElement(FormElementBuilder $form_element){
        return $form_element->render();
    }

    protected function elementObject(String $type) : FormElementBuilder {
        switch ($type) {
            case 'checkbox': return new CheckboxElement(); 
            break;
            case 'color': return new ColorElement();
            break;
            case 'date' : return new DateElement();
            break;
            case 'email' : return new EmailElement();
            break;
            case 'largeHeading' : return new Heading2();
            break;
            case 'smallHeading' : return new Heading4();
            break;
            case 'number' : return new NumberElement();
            break;
            case 'file' : return new FileElement();
            break;
            case 'paragraph' : return new ParagraphElement();
            break;
            case 'radio' : return new RadioElement();
            break;
            case 'select' : return new SelectElement();
            break;
            case 'section' : return new SectionElement();
            break;
            case 'tel' : return new TextElement();
            break;
            case 'text' : return new TextElement();
            break;
            case 'time' : return new TimeElement();
            break;
            case 'textarea' : return new TextAreaElement();
            break;
            case 'url' : return new UrlElement();
            break;
            default:
            return new TextElement();
        break;
        }

    }
}