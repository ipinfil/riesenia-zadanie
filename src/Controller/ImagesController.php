<?php

declare(strict_types=1);
namespace App\Controller;

use App\Form\SearchForm;
use Cake\Utility\Text;
use Cake\Validation\Validation;

/**
 * Images Controller.
 *
 * @property \App\Model\Table\ImagesTable $Images
 *
 * @method \App\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ImagesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function filter($width, $height)
    {
        $images = $this->Images->
            find('sized', [
                'width' => $width,
                'height' => $height,
            ])->all();

        $search = new SearchForm();

        if ($this->request->is('post')) {
            if ($search->execute($this->request->getData())) {
                $search = $this->request->getData('search');

                $search = preg_split('/x/', $search);

                $width = trim($search[0]);
                $height = trim($search[1]);

                return $this->redirect(['action' => 'filter', $width, $height]);
            } else {
                $this->Flash->error('Wrong search format.');
            }
        }

        $this->set(compact('images'))->set(compact('search'));
    }

    /**
     * Index method.
     *
     * @return \Cake\Http\Response|void|null Renders view
     */
    public function index()
    {
        $images = $this->paginate($this->Images);
        $search = new SearchForm();

        if ($this->request->is('post')) {
            if ($search->execute($this->request->getData())) {
                $search = $this->request->getData('search');

                $search = preg_split('/x/', $search);

                $width = trim($search[0]);
                $height = trim($search[1]);

                return $this->redirect(['action' => 'filter', $width, $height]);
            } else {
                $this->Flash->error('Wrong search format.');
            }
        }

        $this->set(compact('images', 'search'));
    }

    /**
     * View method.
     *
     * @param string|null $id image id
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException when record not found
     *
     * @return \Cake\Http\Response|void|null Renders view
     */
    public function view($id = null)
    {
        $image = $this->Images->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('image'));
    }

    /**
     * Add method.
     *
     * @return \Cake\Http\Response|void|null redirects on successful add, renders view otherwise
     */
    public function add()
    {
        $image = $this->Images->newEmptyEntity();

        if ($this->request->is('post')) {
            $image = $this->Images->patchEntity($image, $this->request->getData());
            $height = $this->request->getData('height');
            $width = $this->request->getData('width');

            if (!$image->getErrors()) {
                $file = $this->request->getData('image');
                $top = $this->request->getData('top');
                $left = $this->request->getData('left');

                $img_validator = new Validation();
                $minHeight = $top + $height;
                $minWidth = $left + $width;

                $heightOk = $img_validator->imageHeight($file, '>=', $minHeight);
                $widthOk = $img_validator->imageWidth($file, '>=', $minWidth);

                if ($heightOk && $widthOk) {
                    $this->cropImage($file, $top, $left, $image);

                    if ($this->Images->save($image)) {
                        $this->Flash->success(__('The image has been saved.'));

                        return $this->redirect(['action' => 'index']);
                    }
                } else {
                    $this->Flash->error(__('Image is not large enough to be cropped.'));
                }
            }

            $this->Flash->error(__('The image could not be saved. Please, try again.'));
        }
        $this->set(compact('image'));
    }

    /**
     * Delete method.
     *
     * @param string|null $id image id
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException when record not found
     *
     * @return \Cake\Http\Response|void|null redirects to index
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $image = $this->Images->get($id);

        if ($this->Images->delete($image)) {
            $this->Flash->success(__('The image has been deleted.'));
        } else {
            $this->Flash->error(__('The image could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    private function cropImage($file, $top, $left, $imageEntity)
    {
        $fileName = $file->getClientFilename();
        $mediaType = $file->getClientMediaType();

        $relativePath = 'img' . DS . $fileName;

        if (file_exists(WWW_ROOT . $relativePath)) {
            $fileType = explode('/', $mediaType);
            $relativePath = 'img' . DS . Text::uuid() . '.' . $fileType[1];
        }
        $path = WWW_ROOT . $relativePath;

        $img = imagecreatefromstring($file->getStream()->getContents());
        $croppedimg = imagecrop($img, [
            'x' => $left,
            'y' => $top,
            'width' => $imageEntity->width,
            'height' => $imageEntity->height,
        ]);

        if ($croppedimg) {
            if ($mediaType == 'image/png') {
                imagepng($croppedimg, $path);
            } else {
                imagejpeg($croppedimg, $path);
            }
            $imageEntity->path = $relativePath;
        }
    }
}
