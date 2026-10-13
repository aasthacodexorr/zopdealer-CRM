<?php

namespace App\Controllers;

use App\Composites\LeadTemplateFormComposite;
use Tenant\app\Repositories\TenantUserMetaDataRepository;

/**
 * LeadTemplateController : This controller is reponsible for response and request related to Leads Templates.
 */
class LeadTemplateController extends BaseController{

    
    public function index()
    {
        $leadTemplateFormComposite = new LeadTemplateFormComposite();
        $items = 10;
        $paginate = true;
        if($this->request->getGet('items')){
            $items = $this->request->getGet('items');
        }

        if($this->request->getGet('paginate')){
            $paginate = $this->request->getGet('paginate');
            $paginate  = $paginate == 'false' ? false : true;
        }
        $data = $leadTemplateFormComposite->getAllForms($items,$paginate);

        $this->response
        ->setContentType('application/json')->setJSON($data)->send();
    }


    /**
     * create This function creates the template for Leads.
     *
     * @return void
     */
    public function create() : void
    {
        $data = $this->request->getPost();
        $leadTemplateFormComposite = new LeadTemplateFormComposite();
        $response = $leadTemplateFormComposite->saveElements($data);
       if(isset($response['success'])){
            $this->response->setContentType('application/json')->setJSON($response)->send();
       }else{
           $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
       }

    }
    
    /**
     * show This method shows a single lead template with elements
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id) : void {
        $leadTemplateFormComposite = new LeadTemplateFormComposite();
        $response = $leadTemplateFormComposite->getElements($id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }
    
    /**
     * update This method updates a update lead template with replaces elements 
     *
     * @param  mixed $id
     * @return void
     */
    public function update($id) : void {
        $data = $this->request->getPost();
        $leadTemplateFormComposite = new LeadTemplateFormComposite();
        $response = $leadTemplateFormComposite->saveElements($data, (int) $id);
        if(isset($response['success'])){
            $this->response->setContentType('application/json')->setJSON($response)->send();
       }else{
           $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
       }

    }

    
    /**
     * render Accepts the id of the form to render.
     *
     * @param  mixed $id
     * @return void
     */
    public function render($id)
    {
        $leadTemplateFormComposite = new LeadTemplateFormComposite();
        $form_data = $leadTemplateFormComposite->getElements($id);
        $data['disclosure'] = $form_data['disclosure'];
        $data['source'] = $form_data['source'];
        $data['VOI'] = $form_data['VOI'];
        $data['custom_css'] = $form_data['custom_css'];
        $data['custom_less'] = $form_data['custom_less'];
        $data['form_id'] = $id;
        $data['users'] = (new TenantUserMetaDataRepository())->findAllUsers(["user_metadata.user_id", "user_metadata.name"]);

        $response = $leadTemplateFormComposite->renderElements();
        $data['dom_elements'] = $response;
        if($form_data['override_custom_template'] == '1'){
            if(is_file(APPPATH . 'Views/forms/'. $form_data['custom_template'] .'.php')){
                echo view('forms/' . $form_data['custom_template'], $data);
                exit;
            }

        }
        
        echo view('forms/default', $data);
        
    }

    public function options($any)
    {
        return $this->response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
            ->setHeader('Access-Control-Allow-Headers', '*') //for allow any headers, insecure
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
            ->setStatusCode(200); //status code
    }
}
