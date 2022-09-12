<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\BankTransaction;
use App\Models\Comment;
use App\Models\Department;
use App\Models\MbTransaction;
use App\Models\Media;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreAddon;
use App\Models\Testimonial;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function myOrders()
    {
        $orders = Order::with('user')
            ->where('user_id', Auth::user()->id)
            ->orderBy('id', 'DESC')
            ->get();
        return view('members.my_orders', compact('orders'));
    }

    public function output($str)
    {
        echo $str;
        flush();
        ob_flush();
    }

    public function subscriptionPlanHistory($setupId)
    {
        $subscriptionPlans = Order::with('user')
            ->where('user_id', Auth::user()->id)
            ->where('setup_id', $setupId)
            ->orderBy('id', 'DESC')
            ->get();

        $view = view('members.table_subscription_plans', compact('subscriptionPlans'));
        return ['status' => 'OK', 'html' => $view->render()];
    }

    /**
     * Update a user in the storage.
     *
     * @param int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function updateProfile($id, Request $request)
    {
        $data = $this->getProfileData($request);
        try {
            // update existing users
            $user = User::findOrFail($id);
            $user->update($data);

            return redirect()
                ->route('members.account')
                ->with('success_message', 'Your profile is successfully updated!');

        } catch (Exception $exception) {
            return back()
                ->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    public function account()
    {
        $customer = User::findOrFail(Auth::user()->id);

        return view('members.profile', compact('customer'));
    }

    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getProfileData(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:1|max:200',
            'phone' => 'required|numeric',
            'address' => 'required|string|min:1|max:300',
            'email' => 'required|string|min:1|max:300',
        ];

        if (!empty($request->input('password'))) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $data = $request->validate($rules);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }

    /**
     * Update password for a user in the storage.
     *
     * @param int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function changePassword($id, Request $request)
    {
        // Get current user
        $user = User::findOrFail($id);
        // Validate
        $data = $this->getPasswordData($request, $user);
        try {

            // update password
            $user->update($data);

            return redirect()
                ->route('members.account')
                ->with('success_message', 'Your password is successfully updated!');
        } catch (Exception $exception) {
            return back()
                ->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @param User $user
     * @return array
     */
    protected function getPasswordData(Request $request, $user)
    {
        $rules = [
            'new_password' => 'required|string|min:6|max:30',
            'confirm_password' => 'required|min:6|same:new_password',
            'current_password' => ['required', 'min:6', 'max:30', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
                return false;
            }],
        ];

        $data = $request->validate($rules);

        if (!empty($data['new_password'])) {
            $data['password'] = Hash::make($data['new_password']);
        }

        return $data;
    }

    public function myTickets()
    {
        $tickets = Ticket::with('department', 'creator')
            ->where('created_by', Auth::user()->id)
            ->orderBy('id', 'DESC')
            ->get();
        return view('members.my_tickets', compact('tickets'));
    }

    public function createTicket()
    {
        $products = Product::where('status', 'Active')->pluck('title', 'id');
        $departments = Department::all()->pluck('name', 'id');
        $ticket = null;
        return view('members.create_ticket', compact('departments', 'ticket', 'products'));
    }

    /**
     * Store a new ticket in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function storeTicket(Request $request)
    {
        try {
            $data = $this->getData($request);
            $data['created_by'] = Auth::user()->id;
            Ticket::create($data);

            return redirect()
                ->route('members.myTickets')
                ->with('success_message', 'Ticket was successfully added!');
        } catch (Exception $exception) {
            return back()
                ->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    public function editMbTransaction($orderId)
    {
        $mbTransaction = MbTransaction::where([
            'user_id' => Auth::user()->id,
            'order_id' => $orderId,
        ])->first();

        $view = view('members.mb_transaction.edit', compact('mbTransaction', 'orderId'));
        return ['status' => 'OK', 'html' => $view->render()];
    }

    /**
     * Store bank information.
     *
     * @param int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function updateMbTransaction($id, Request $request)
    {
        $data = $this->getMobileBankingData($request, $id);
        try {
            $mbTransaction = MbTransaction::findOrFail($id);
            $mbTransaction->update($data);

            return redirect()
                ->route('members.myOrders')
                ->with('success_message', 'Mobile banking transaction updated successfully!');
        } catch (Exception $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'unexpected_error' => 'Unexpected error occurred while trying to process your request!',
                ]);
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getMobileBankingData(Request $request)
    {
        return $request->validate([
            'sender_mobile' => 'required|string|min:1|max:255',
            'amount' => 'required|numeric',
            'transaction_id' => 'required|string|min:1|max:255',
        ]);
    }

    public function createOrEditBankTransaction($orderId)
    {
        $bankTransaction = BankTransaction::where('user_id', Auth::user()->id)
            ->where('order_id', $orderId)
            ->first();

        if (!empty($bankTransaction)) {
            $view = view('members.bank_transaction.edit', compact('bankTransaction', 'orderId'));
        } else {
            $view = view('members.bank_transaction.create', compact('bankTransaction', 'orderId'));
        }

        return ['status' => 'OK', 'html' => $view->render()];
    }

    /**
     * Store bank information.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function storeBankTransaction(Request $request)
    {
        $data = $this->getBankTransactionData($request);
        try {
            $data['user_id'] = Auth::user()->id;
            $uploadedFile = CommonHelper::uploadMedia($request, 'attachment', 'bank_transactions');
            if (!empty($uploadedFile)) {
                $data['media_id'] = $uploadedFile->id;
            }
            BankTransaction::create($data);

            return redirect()
                ->route('members.myOrders')
                ->with('success_message', 'Bank transaction details saved successfully!');
        } catch (Exception $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'unexpected_error' => 'Unexpected error occurred while trying to process your request!',
                ]);
        }
    }

    /**
     * Store bank information.
     *
     * @param int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function updateBankTransaction($id, Request $request)
    {
        $data = $this->getBankTransactionData($request, $id);
        try {
            $bankTransaction = BankTransaction::findOrFail($id);

            if ($request->hasFile('attachment')) {
                // Delete existing media
                $media = Media::find($bankTransaction->media_id);
                if (!empty($media)) {
                    File::delete(public_path('storage/' . $media->file_dir) . $media->filename);
                    $media->delete();
                }

                $uploadedFile = CommonHelper::uploadMedia($request, 'attachment', 'bank_transactions');
                if (!empty($uploadedFile)) {
                    $data['media_id'] = $uploadedFile->id;
                }
            }

            $bankTransaction->update($data);

            return redirect()
                ->route('members.myOrders')
                ->with('success_message', 'Bank transaction details saved successfully!');
        } catch (Exception $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'unexpected_error' => 'Unexpected error occurred while trying to process your request!',
                ]);
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    protected function getBankTransactionData(Request $request, $id = 0)
    {
        $rules = [
            'reference' => 'required|string|min:1|max:255',
            'amount' => 'required|numeric',
            'comments' => 'nullable|string|min:1|max:255',
        ];

        if ($id == 0 || $request->hasFile('attachment')) {
            $rules['attachment'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:500000000';
        }

        if ($id == 0) {
            $rules['order_id'] = 'required|numeric';
        }

        return $request->validate($rules);
    }

    /**
     * Remove the specified order from the storage.
     *
     * @param int $id
     * @return RedirectResponse | Redirector
     * @throws Exception
     */
    public function destroyOrder($id)
    {
        try {
            $order = Order::findOrFail($id);
            if (!empty($order->store_id)) {
                $store = Store::findOrFail($order->store_id);
            }

            if ($order->status != 'Pending') {
                return redirect()->route('members.myOrders')
                    ->with('error_message', 'You are not allowed to remove this!');
            }

            // check for addons which were requested from store and delete them
            if ($order->package_type == 'addon') {
                StoreAddon::whereIn('order_id', [$id])->delete();
            }

            // Check whether any subscription plans
            $subscriptionPlans = Order::where('setup_id', $order->id)->where(function ($q) {
                $q->where('package_type', 'web_plan');
                $q->orWhere('package_type', 'android_plan');
                $q->orWhere('package_type', 'ios_plan');
            })->get();
            if ($subscriptionPlans->isNotEmpty()) {
                return redirect()->route('members.myOrders')
                    ->with('error_message', 'You already have purchase plan for this installation package!');
            }

            // Check whether any bank information
            $bankInformation = BankTransaction::where([
                'user_id' => Auth::user()->id,
                'order_id' => $order->id,
            ])->first();
            if (!empty($bankInformation)) {
                // Delete existing media
                $media = Media::find($bankInformation->media_id);
                if (!empty($media)) {
                    File::delete(public_path('storage/' . $media->file_dir) . $media->filename);
                    $media->delete();
                }
                $bankInformation->delete();
            }

            // Check whether any mobile banking informations
            $mbTransaction = MbTransaction::where([
                'user_id' => Auth::user()->id,
                'order_id' => $order->id,
            ])->first();
            if (!empty($mbTransaction)) {
                $mbTransaction->delete();
            }

            $order->delete();

            return redirect()->route('members.myOrders')
                ->with('success_message', 'Order was successfully deleted!');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors([
                    'unexpected_error' => 'Unexpected error occurred while trying to process your request!',
                ]);
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|min:1|max:255',
            'department_id' => 'required',
            'product_id' => 'numeric',
            'priority' => 'required',
            'message' => 'required',
        ]);

        return $data;
    }

    public function viewTicket($ticketId)
    {
        $ticket = Ticket::with('department', 'creator')
            ->where('created_by', Auth::user()->id)
            ->findOrFail($ticketId);

        $comments = Comment::where('ticket_id', $ticketId)->orderBy('created_at', 'DESC')->get();

        if ($ticket->customer_action == 'Unread') {
            $ticket->update(['customer_action' => 'Read']);
        }

        return view('members.view_tickets', compact('ticket', 'comments'));
    }

    /**
     * Store a new comment in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function storeComment(Request $request)
    {
        try {
            $data = $this->getCommentData($request);
            $data['user_id'] = Auth::user()->id;

            $ticketId = $data['ticket_id'];

            Comment::create($data);

            $ticket = Ticket::findOrFail($ticketId);
            $ticket->update(['agent_action' => 'Not Answered']);

            return redirect()
                ->route('members.viewTicket', $ticketId)
                ->with('success_message', 'Your reply is added successfully!');
        } catch (Exception $exception) {
            return back()
                ->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getCommentData(Request $request)
    {
        $data = $request->validate([
            'ticket_id' => 'required',
            'message' => 'required',
        ]);

        return $data;
    }

    public function printInvoice($id)
    {
        set_time_limit(300);
        $order = Order::findOrFail($id);
        $shoppingCart = json_decode($order->shopping_cart, true);
        if (!empty($shoppingCart['theme'])) {
            $shoppingCart['theme'] = optional(Product::find($shoppingCart['theme']))->title;
        }

        $view = view('members.print_invoice', compact('order', 'shoppingCart'));
        CommonHelper::generatePdf($view->render(), 'invoice-' . date('Ymd'));
    }

    public function createTestimonial()
    {
        $departments = Department::all()->pluck('name', 'id');
        $ticket = null;
        $product = Product::where('status', 'Active')->first();
        return view('members.create_testimonial', compact('departments', 'ticket', 'product'));
    }

    /**
     * Store a new testimonial in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function storeTestimonial(Request $request)
    {
        try {
            $data = $this->getTestimonialData($request);
            $data['customer_id'] = Auth::user()->id;
            $data['status'] = 'Inactive';
            Testimonial::create($data);

            return redirect()
                ->route('members.myTickets')
                ->with('success_message', 'Your testimonial was successfully submitted and waiting for approval.');
        } catch (Exception $exception) {
            return back()
                ->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getTestimonialData(Request $request)
    {
        $data = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'message' => 'required',
        ]);

        return $data;
    }

    // verify user email
    public function verifyUser($token)
    {

        $user = User::where('remember_token', $token)->first();
        if (!is_null($user)) {
            $user->remember_token = null;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->status = 'Active';
            $user->save();
            return redirect()->route('login')
                ->with('info_message', 'Your account is activated Successfully! You can login now.');
        } else {
            session()->flash('info_message', 'Invalid token.You may have already activated your account!');
            return redirect()->route('login');
        }
    }
}
