<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;





final class TodoController extends AbstractController
{
  
    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();

        if (!$session->has('todos')) {
            $todo = [
                'Learn' => 'Learn symfony in formation',
                'french language' => 'practice the french language words',
                'podcast' => 'listen to the podcast'
            ];
            $session->set('todos', $todo);
            $this->addFlash('info', 'the todo list is just created ');
        }

        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
        ]);
    }

// #[Route('/todo/add/{name}/{content}', name: 'todo:add')]
// #[Route('/todo/add/{name}/{content}', name: 'todo:add', defaults:['toujour'=> 'Dormir', 'content'=> 'Dormir'])]  here it pass default value for the content of the todo list
#[Route('/todo/add/{name?test}/{content?test}', name: 'todo:add')] // the esay syntaxt for default values
public function addTodo(Request $request, $name, $content):RedirectResponse
    {
        $session = $request->getSession(); // this line access the request variable sent by the user via url 

        if ($session->has('todos')) {
            $todos = $session->get('todos');
            // i verify if the session has the todo array inside it 
            if (isset($todos[$name])) {
                //here i verify if i have already the same todo in the list 
                $this->addFlash('error', 'there is already a todo list');
            } else { // if it is a new todo i add it to my todo list that i had before 
                $todos[$name] = $content; // this line adds a new entry in the todo array list 
                $this->addFlash('success', "the todo list $name has been added successfully");
                $session->set('todos', $todos); // this line will update the seesion with the new/updated todo list
                
            }
        } else {
            $this->addFlash('error', 'there is no todo list');
        }

        return $this->redirectToRoute('app_todo');
    }



//Here i try to do update functionality via url request 
#[Route('/todo/update/{name}/{content}', name: 'todo:update')]
    public function updateTodo(Request $request, $name, $content): RedirectResponse
    {
        $session = $request->getSession(); // this line access the request variable sent by the user via url 

        if ($session->has('todos')) {
            $todos = $session->get('todos');
            // i verify if the session has the todo array inside it 
            if (!isset($todos[$name])) {
                //here i verify if i have already the same todo in the list 
                $this->addFlash('error', "there is no $name  todo list");
            } else { // if it is a new todo i add it to my todo list that i had before 
                $todos[$name] = $content; // this line adds a new entry in the todo array list 
                $this->addFlash('success', "the todo list $name has been update successfully");
                $session->set('todos', $todos); // this line will update the seesion with the new/updated todo list
                
            }
        } else {
            $this->addFlash('error', 'there is no todo list');
        }

        return $this->redirectToRoute('app_todo');
    }

//Here i try to do delee functionality via url request 
#[Route('/todo/delete/{name}', name: 'todo:delete')]
public function deleteTodo(Request $request, $name): RedirectResponse
    {
        $session = $request->getSession(); // this line access the request variable sent by the user via url 

        if ($session->has('todos')) {
            $todos = $session->get('todos');
            // i verify if the session has the todo array inside it 
            if (!isset($todos[$name])) {
                //here i verify if i have already the same todo in the list 
                $this->addFlash('error', "there is no $name  todo list");
            } else { // if it is a new todo i add it to my todo list that i had before 
                unset($todos[$name]);
                $this->addFlash('success', "the todo list $name has been deleted successfully");
                $session->set('todos', $todos); // this line will update the seesion with the new/updated todo list
                
            }
        } else {
            $this->addFlash('error', 'there is no todo list');
        }

        return $this->redirectToRoute('app_todo');
    }

#[Route('/todo/reset', name: 'todo:reset')]
public function resetTodo(Request $request): RedirectResponse // this class (RedirectResponse) redirect us to specified url (class is used to create an HTTP response that performs a redirection to a different URL)
    {
        $session = $request->getSession(); // this line access the request variable sent by the user via url 
        $session->remove(name:'todos');
        return $this->redirectToRoute('app_todo');
    }


    
}
